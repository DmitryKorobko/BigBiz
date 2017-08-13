<?php
namespace common\models\comment\repositories;

use common\models\{
    comment\CommentEntity, comment_like\CommentLikeEntity, shop_profile\ShopProfileEntity,
    user_profile\UserProfileEntity
};
use yii\data\ArrayDataProvider;

/**
 * Class CommonCommentRepository
 *
 * @package common\models\comment\repositories
 */
trait CommonCommentRepository
{
    /**
     * Method of getting list comments of theme
     *
     * @param integer $themeId
     * @return ArrayDataProvider | array
     */
    public function getListComments($themeId)
    {
        /** @var  $commentLikeModel CommentLikeEntity.php */
        $commentLikeModel = new CommentLikeEntity();

        $comments = [];
        $models = CommentEntity::find()
            ->select(['comment.id', 'text', 'recipient_id', 'created_by', 'comment.created_at', 'comment.status',
                'user.status_online as status_online'])
            ->leftJoin('user', 'user.id = comment.created_by')
            ->where(['theme_id' => $themeId])
            ->orderBy(['comment.created_at' => SORT_DESC])
            ->asArray()
            ->all();

        if ($models) {
            foreach ($models as $model) {
                $model['count_like'] = $commentLikeModel->getCountCommentLike($model['id']);
                $model['liked'] = $commentLikeModel->isLikedCommentByUser($model['id']);

                /** Get information about author of comment */
                $authorShop = ShopProfileEntity::findOne(['user_id' => $model['created_by']]);
                $authorUser = UserProfileEntity::findOne(['user_id' => $model['created_by']]);
                if ($authorShop) {
                    $author = [
                        'name'          => $authorShop->name,
                        'avatar'        => $authorShop->image,
                        'status_online' => $model['status_online']
                    ];
                } else if ($authorUser) {
                    $author = [
                        'name'   => $authorUser->nickname,
                        'avatar' => $authorUser->avatar,
                        'status_online' => $model['status_online']
                    ];
                } else {
                    $author = [
                        'name'   => 'Администрация',
                        'avatar' => null,
                        'status_online' => $model['status_online']
                    ];
                }

                $recipient = [];
                if ($model['recipient_id']) {
                    /** Get information about recipient of comment */
                    $recipientShop = ShopProfileEntity::findOne(['user_id' => $model['recipient_id']]);
                    if ($recipientShop) {
                        $recipient = [
                            'name' => $recipientShop->name
                        ];
                    } else {
                        $recipientUser = UserProfileEntity::findOne(['user_id' => $model['recipient_id']]);
                        $recipient = [
                            'name' => $recipientUser->nickname
                        ];
                    }
                }

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
                'pageSize' => 10
            ]
        ]);

        return $dataProvider;
    }
}