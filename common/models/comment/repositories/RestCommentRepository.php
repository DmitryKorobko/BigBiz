<?php
namespace common\models\comment\repositories;

use common\models\{
    comment\CommentEntity, comment_image\CommentImageEntity, comment_like\CommentLikeEntity,
    shop_profile\ShopProfileEntity, user_profile\UserProfileEntity, admin_contact\AdminContactEntity
};
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use Yii;

/**
 * Class RestCommentRepository
 *
 * @package common\models\comment\repositories
 */
trait RestCommentRepository
{
    /**
     * Method of generating unique imageName
     *
     * @param $folder
     * @param $fileType
     * @return string
     */
    private function generateUniqueImageName($folder, $fileType)
    {
        return $folder . '/' . Yii::$app->security->generateRandomString() . '.' . $fileType;
    }

    /**
     * Method of generating pre-signed urls for comment images
     *
     * @param $images
     * @return array
     */
    public function generatePreSignedUrls($images)
    {
        $urls = [];
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        if (!empty($images)) {
            foreach ($images as $image) {
                $fileName = $this->generateUniqueImageName(Yii::$app->params['aws_asset_comment_path'],
                    $image['fileType']);
                $bucketName = Yii::$app->params['bucketName'];
                $urls[] = [
                    'url'       => "http://${bucketName}.s3.us-west-2.amazonaws.com/${fileName}",
                    'signedUrl' => $s3->commands()->getPresignedUrl($fileName, '+2 days')->execute(),
                    'sort'      => $image['sort']
                ];
            }
        }
        return $urls;
    }

    /**
     * Method of getting detailed comment information after updating
     *
     * @return array
     * @throws NotFoundHttpException
     * @param $postData
     */
    public function getUpdatedCommentDetail($postData): array
    {
        /** @var  $comment CommentEntity*/
        $comment = self::findOne($postData['comment_id']);
        $commentImages = [];
        if (!$comment) {
            throw new NotFoundHttpException('Комментарий не найден.');
        }

        if (isset($postData['images']['create'])) {
            $createImagesSrc = [];
            foreach ($postData['images']['create'] as $item) {
                $createImagesSrc[] = $item['src'];
            }

            $createCommentImages = $comment->getImages()->select([
                'comment_image.id', 'comment_image.src'])->where(['in', 'src', $createImagesSrc])->all();
            foreach ($createCommentImages as $item) {
                $commentImages['images']['created'][] = [
                    'id'  => $item['id'],
                    'src' => $item['src']
                ];
            }
        }
        Yii::$app->response->setStatusCode(200, 'OK');

        return [
            'status'  => Yii::$app->response->statusCode,
            'message' => 'Комментарий успешно изменён',
            'data'    => array_merge($comment->toArray(), $commentImages)
        ];
    }

    /**
     * Method of getting detailed comment information after creating
     *
     * @return array
     * @throws NotFoundHttpException
     * @param $id
     */
    public function getCreatedCommentDetail($id): array
    {
        /** @var  $commentLikeModel CommentLikeEntity.php */
        $commentLikeModel = new CommentLikeEntity();

        $comment = [];

        /** @var  $model CommentEntity*/
        $model = CommentEntity::find()
            ->select([
                'comment.id',
                'comment.text',
                'comment.recipient_id',
                'comment.created_by',
                'comment.created_at',
                'comment.status',
                'user.status_online as user_status_online'
            ])
            ->leftJoin('user', 'comment.created_by = user.id')
            ->where(['comment.id' => $id])
            ->asArray()
            ->one();

        $model['count_like'] = $commentLikeModel->getCountCommentLike($model['id']);
        $model['liked'] = $commentLikeModel->isLikedCommentByUser($model['id']);
        $model['images'] = CommentImageEntity::find()
            ->select('src')
            ->where(['comment_id' => $model['id']])
            ->asArray()
            ->all();

        /** Get information about author of comment */
        $authorShop = ShopProfileEntity::findOne(['user_id' => $model['created_by']]);
        $authorUser = UserProfileEntity::findOne(['user_id' => $model['created_by']]);
        if ($authorShop) {
            $author = [
                'id'            => $model['created_by'],
                'name'          => $authorShop->name,
                'avatar'        => $authorShop->image,
                'status_online' => $model['user_status_online']
            ];
        } elseif ($authorUser) {
            $author = [
                'id'            => $model['created_by'],
                'name'          => $authorUser->nickname,
                'avatar'        => $authorUser->avatar,
                'status_online' => $model['user_status_online']
            ];
        } else {
            $author = [
                'name'          => 'Администрация',
                'avatar'        => (new AdminContactEntity())->getCurrentImage($model['created_by']),
                'status_online' => $model['user_status_online']
            ];
        }

        if ($model['recipient_id']) {
            /** Get information about recipient of comment */
            $recipientShop = ShopProfileEntity::findOne(['user_id' => $model['recipient_id']]);
            $recipientUser = UserProfileEntity::findOne(['user_id' => $model['recipient_id']]);
            if ($recipientShop) {
                $recipient = [
                    'id'   => $recipientShop->user_id,
                    'name' => $recipientShop->name
                ];
            } elseif($recipientUser) {
                $recipient = [
                    'id'   => $recipientUser->user_id,
                    'name' => $recipientUser->nickname
                ];
            } else {
                $recipient = [
                    'name' => 'Администрация'
                ];
            }
        } else {
            $recipient = null;
        }
        unset($model['recipient_id'], $model['created_by']);

        $comment[] = [
            'recipient' => $recipient,
            'author'    => $author,
            'comment'   => $model
        ];

        return $comment;
    }

    /**
     * Method of getting list comments of theme
     *
     * @param integer $themeId
     * @return ArrayDataProvider
     */
    public function getComments($themeId)
    {
        /** @var  $commentLikeModel CommentLikeEntity.php */
        $commentLikeModel = new CommentLikeEntity();

        $comments = [];
        $models = CommentEntity::find()
            ->select([
                'comment.id',
                'comment.text',
                'comment.recipient_id',
                'comment.created_by',
                'comment.created_at',
                'comment.status',
                'user.status_online as user_status_online'
            ])
            ->where(['theme_id' => $themeId])
            ->leftJoin('user', 'comment.created_by = user.id')
            ->orderBy(['comment.created_at' => SORT_ASC])
            ->asArray()
            ->all();

        if ($models) {
            foreach ($models as $model) {
                $model['count_like'] = $commentLikeModel->getCountCommentLike($model['id']);
                $model['liked'] = $commentLikeModel->isLikedCommentByUser($model['id']);
                $model['images'] = CommentImageEntity::find()
                    ->select('src')
                    ->where(['comment_id' => $model['id']])
                    ->asArray()
                    ->all();

                /** Get information about author of comment */
                $authorShop = ShopProfileEntity::findOne(['user_id' => $model['created_by']]);
                $authorUser = UserProfileEntity::findOne(['user_id' => $model['created_by']]);
                if ($authorShop) {
                    $author = [
                        'id'            => $model['created_by'],
                        'name'          => $authorShop->name,
                        'avatar'        => $authorShop->image,
                        'status_online' => $model['user_status_online']
                    ];
                } elseif ($authorUser) {
                    $author = [
                        'id'            => $model['created_by'],
                        'name'          => $authorUser->nickname,
                        'avatar'        => $authorUser->avatar,
                        'status_online' => $model['user_status_online']
                    ];
                } else {
                    $author = [
                        'name'          => 'Администрация',
                        'avatar'        => (new AdminContactEntity())->getCurrentImage($model['created_by']),
                        'status_online' => $model['user_status_online']
                    ];
                }

                if ($model['recipient_id']) {
                    /** Get information about recipient of comment */
                    $recipientShop = ShopProfileEntity::findOne(['user_id' => $model['recipient_id']]);
                    $recipientUser = UserProfileEntity::findOne(['user_id' => $model['recipient_id']]);
                    if ($recipientShop) {
                        $recipient = [
                            'id'   => $recipientShop->user_id,
                            'name' => $recipientShop->name
                        ];
                    } elseif ($recipientUser) {
                        $recipient = [
                            'id'   => $recipientUser->user_id,
                            'name' => $recipientUser->nickname
                        ];
                    } else {
                        $recipient = [
                            'name' => 'Администрация'
                        ];
                    }
                } else {
                    $recipient = null;
                }
                unset($model['recipient_id'], $model['created_by'], $model['user_status_online']);

                $comments[] = [
                    'recipient' => $recipient,
                    'author'    => $author,
                    'comment'   => $model
                ];

            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $comments,
            'pagination' => [
                'pageSize' => Yii::$app->request->queryParams['per-page'] ?? 10
            ]
        ]);

        return $dataProvider;
    }
}