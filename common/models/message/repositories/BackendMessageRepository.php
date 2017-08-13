<?php
namespace common\models\message\repositories;

use common\models\{
    message\MessageEntity, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity, user\repositories\UserRepository
};
use Yii;

/**
 * Class BackendMessageRepository
 *
 * @package common\models\message\repositories
 */
trait BackendMessageRepository
{
    /**
     *  Method of getting list of chats by recipient user
     *
     * @param $status
     * @return array
     */
    public function getListRecipientChats($status = null): array
    {
        $creatorIdFlags = [];
        $messages = [];
        $query = MessageEntity::find()
            ->select(['message.id', 'message.text', 'message.recipient_id', 'message.created_at',
                'shop_profile.name as recipient_name', 'message.created_by'])
            ->leftJoin('shop_profile', 'shop_profile.user_id = message.recipient_id')
            ->where(['recipient_id' => Yii::$app->user->identity->getId()]);

        if (!empty($status)) {
            $query->andWhere(['status' => $status]);
        }

        $models = $query->orderBy(['message.created_at' => SORT_DESC])->asArray()->all();

        if ($models) {
            foreach ($models as $model) {
                if (!isset($creatorIdFlags[($model['created_by'])])){
                    /** @var  $shop ShopProfileEntity */
                    $shop = ShopProfileEntity::findOne(['user_id' => $model['created_by']]);
                    if ($shop) {
                        if (!isset($messages[$model['created_by']])) {
                            $messages[$model['created_by']] = [
                                'creator' => [
                                    'name' => $shop->name,
                                    'avatar' => $shop->image,
                                    'is_online'  => UserRepository::isOnline($model['created_by'])
                                ],
                                'text' => $model['text'],
                                'recipient_name' => $model['recipient_name'],
                                'created_at' => $model['created_at']
                            ];
                        }
                    } else {
                        $user = UserProfileEntity::findOne(['user_id' => $model['created_by']]);
                        if (!isset($messages[$model['created_by']])) {
                            $messages[$model['created_by']] = [
                                'creator' => [
                                    'name' => $user->nickname,
                                    'avatar' => $user->avatar,
                                    'is_online'  => UserRepository::isOnline($model['created_by'])
                                ],
                                'text' => $model['text'],
                                'recipient_name' => $model['recipient_name'],
                                'created_at' => $model['created_at']
                            ];
                        }
                    }

                    $creatorIdFlags[($model['created_by'])] = 1;
                }
            }

            return $messages;
        }

        return $messages;
    }
}