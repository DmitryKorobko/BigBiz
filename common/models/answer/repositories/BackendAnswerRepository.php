<?php
namespace common\models\answer\repositories;

use Yii;
use common\models\{
    answer\AnswerEntity, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity,
    user\repositories\UserRepository
};
/**
 * Class BackendAnswerRepository
 *
 * @package common\models\answer\repositories
 */
trait BackendAnswerRepository
{
    /**
     * Method of getting list of answers.
     *
     * @param $recipientId
     * @param $type
     * @param $status
     * @return array
     */
    public function getListAnswersByType($status, $recipientId = true, $type = null): array
    {
        $answers = [];

        $query = AnswerEntity::find()
            ->select([
                'answer.id', 'answer.type', 'answer.recipient_id', 'answer.product_id',
                'user_profile.nickname as user_name', 'shop_profile.name as shop_name',
                'answer.theme_id', 'answer.comment_id', 'answer.created_by',
                'answer.text', 'answer.status', 'answer.created_at'
            ])
            ->leftJoin('user_profile', 'user_profile.user_id = answer.created_by')
            ->leftJoin('shop_profile', 'shop_profile.user_id = answer.created_by')
            ->leftJoin('comment', 'comment.id = answer.comment_id')
            ->where(['answer.status' => $status]);

        if ($recipientId) {
            $models = $query->andWhere(['answer.recipient_id' => Yii::$app->user->identity->getId()]);
        }

        if ($type) {
            $models = $query->andWhere(['answer.type' => $type])->orderBy(['answer.created_at' => 'desc'])
                ->asArray()->all();
        } else {
            $models = $query->orderBy(['answer.created_at' => 'desc'])->asArray()->all();
        }

        if ($models) {
            foreach ($models as $answer) {
                $answerWithoutUserName = [
                    'id'         => $answer['id'],
                    'type'       => $answer['type'],
                    'product_id' => $answer['product_id'],
                    'theme_id'   => $answer['theme_id'],
                    'comment_id' => $answer['comment_id'],
                    'created_at' => $answer['created_at'],
                    'text'       => $answer['text'],
                    'status'     => $answer['status']
                ];

                /**
                 * @var  $shop ShopProfileEntity
                 * @var  $user UserProfileEntity
                 */
                $shop = ShopProfileEntity::findOne(['user_id' => $answer['created_by']]);
                $user = UserProfileEntity::findOne(['user_id' => $answer['created_by']]);

                if ($shop) {
                    $answerWithoutUserName['creator'] = [
                        'name' => $shop->name,
                        'avatar' => $shop->image,
                        'is_online'  => UserRepository::isOnline($answer['created_by'])
                    ];
                } else {
                    $answerWithoutUserName['creator'] = [
                        'name' => $user->nickname,
                        'avatar' => $user->avatar,
                        'is_online'  => UserRepository::isOnline($answer['created_by'])
                    ];
                }

                $answers[] = $answerWithoutUserName;
            }
        }

        return $answers;
    }

    /**
     * Method of getting count new comment replies
     *
     * @return int
     */
    public function getCountNewCommentReplies(): int
    {
        return AnswerEntity::find()
            ->where([
                'type'         => AnswerEntity::TYPE_REPLY_COMMENT,
                'status'       => self::STATUS_UNREAD,
                'recipient_id' => Yii::$app->user->identity->getId()
            ])->count();
    }
}
