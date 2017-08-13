<?php
namespace common\models\feedback\repositories;

use common\models\{
    feedback\Feedback, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity, user\UserEntity
};

/**
 * Class BackendFeedbackRepository
 *
 * @package common\models\feedback\repositories
 */
trait BackendFeedbackRepository
{

    /**
     * Method of getting count new feedbacks
     *
     * @return int
     */
    public function getCountNewFeedbacks(): int
    {
        return (int) Feedback::find()
            ->where([
                'status' => self::STATUS_UNREAD
            ])
            ->count();
    }

    /**
     *  Method of getting list of feedbacks
     *
     * @param $status
     * @return array
     */
    public function getListFeedbacks($status): array
    {
        $creatorIdFlags = [];
        $feedbacks = [];
        $models = Feedback::find()
            ->select(['id', 'message', 'user_id', 'created_at', 'name'])
            ->where(['feedback.status' => $status])
            ->orderBy(['feedback.created_at' => SORT_DESC])
            ->asArray()
            ->all();

        if ($models) {
            foreach ($models as $model) {
                if (!isset($creatorIdFlags[($model['user_id'])])){
                    /** @var  $shop ShopProfileEntity */
                    $shop = ShopProfileEntity::findOne(['user_id' => $model['user_id']]);
                    if ($shop) {
                        if (!isset($feedbacks[$model['user_id']])) {
                            $feedbacks[$model['user_id']] = [
                                'name'       => $model['name'],
                                'creator'    => [
                                    'name'      => $shop->name,
                                    'avatar'    => $shop->image,
                                    'is_online' => UserEntity::isOnline($model['user_id'])
                                ],
                                'message'    => $model['message'],
                                'created_at' => $model['created_at']
                            ];
                        }
                    } else {
                        $user = UserProfileEntity::findOne(['user_id' => $model['user_id']]);
                        if (!isset($feedbacks[$model['user_id']])) {
                            $feedbacks[$model['user_id']] = [
                                'name'       => $model['name'],
                                'creator'    => [
                                    'name'      => $user->nickname,
                                    'avatar'    => $user->avatar,
                                    'is_online' => UserEntity::isOnline($model['user_id'])
                                ],
                                'message'    => $model['message'],
                                'created_at' => $model['created_at']
                            ];
                        }
                    }

                    $creatorIdFlags[($model['user_id'])] = 1;
                }
            }

            return $feedbacks;
        }

        return $feedbacks;
    }
}