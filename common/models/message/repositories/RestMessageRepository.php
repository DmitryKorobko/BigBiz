<?php

namespace common\models\message\repositories;

use common\models\{
    message\MessageEntity, message_image\MessageImageEntity, shop_profile\ShopProfileEntity,
    user_profile\UserProfileEntity, admin_contact\AdminContactEntity
};
use rest\models\RestUser;
use yii\data\ArrayDataProvider;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class RestMessageRepository
 *
 * @package common\models\message\repositories
 */
trait RestMessageRepository
{
    /**
     * Method of getting chat history by recipientID
     *
     * @param $recipientId
     * @return ArrayDataProvider
     */
    public function getChatHistoryByUser($recipientId): ArrayDataProvider
    {
        $models = MessageEntity::find()
            ->select(['id', 'text', 'status', 'created_at'])
            ->where(['recipient_id' => $recipientId, 'created_by' => \Yii::$app->user->identity->getId()])
            ->orderBy(['created_at' => SORT_DESC])->asArray()->all();

        $messages = [];
        if (!empty($models)) {
            $ids = [];
            foreach ($models as $model) {
                if ($model['status'] === self::STATUS_MESSAGE_UNREAD) {
                    $ids[] = $model['id'];
                }

                $message = [
                    'id'         => (int) $model['id'],
                    'text'       => $model['text'],
                    'status'     => $model['status'],
                    'created_at' => (int) $model['created_at']
                ];
                $message['images'] = MessageImageEntity::find()
                    ->select('src')
                    ->where(['message_id' => $model['id']])
                    ->asArray()
                    ->all();

                $messages[] = $message;
            }
            $this->setStatusForMessages($ids);
        }

        return new ArrayDataProvider([
            'allModels'  => $messages,
            'pagination' => [
                'pageSize' => isset(Yii::$app->request->queryParams['per-page'])
                    ? Yii::$app->request->queryParams['per-page'] : 20
            ]
        ]);
    }

    /**
     * Method of set status of messages like 'READ'
     *
     * @param $ids
     * @return int
     */
    private function setStatusForMessages(array $ids): int
    {
        return self::updateAll(['status' => self::STATUS_MESSAGE_READ], ['in', 'id', $ids]);
    }

    /**
     *  Method of getting list of chats by user
     *
     * @return ArrayDataProvider
     */
    public function getListChats(): ArrayDataProvider
    {
        $messages = [];
        $models = MessageEntity::find()
            ->select(['message.id', 'message.text', 'message.recipient_id', 'message.created_at'])
            ->where(['created_by' => \Yii::$app->user->identity->getId()])
            ->orderBy(['message.created_at' => SORT_DESC])
            ->all();

        if ($models) {
            foreach ($models as $model) {
                $model = $model->toArray();
                $statusOnline = RestUser::findOne(['id' => $model['recipient_id']])->status_online;
                $countNewMessages = $this->countNewMessagesByUser($model['recipient_id']);

                /** @var  $shop ShopProfileEntity */
                $shop = ShopProfileEntity::findOne(['user_id' => $model['recipient_id']]);
                /** @var  $user UserProfileEntity*/
                $user = UserProfileEntity::findOne(['user_id' => $model['recipient_id']]);
                if ($shop) {
                    if (!isset($messages[$model['recipient_id']])) {
                        $message = [
                            'recipient' => [
                                'name'          => $shop->name,
                                'avatar'        => $shop->image,
                                'status_online' => $statusOnline
                            ],
                            'last_message' => $model,
                            'count_new_messages' => $countNewMessages
                        ];
                    }
                } elseif ($user) {
                    if (!isset($messages[$model['recipient_id']])) {
                        $message = [
                            'recipient' => [
                                'name'          => $user->nickname,
                                'avatar'        => $user->avatar,
                                'status_online' => $statusOnline
                            ],
                            'last_message' => $model,
                            'count_new_messages' => $countNewMessages
                        ];
                    }
                } else {
                    $message = [
                        'recipient' => [
                            'name'          => 'Администрация',
                            'avatar'        => (new AdminContactEntity())->getCurrentImage($model['recipient_id']),
                            'status_online' => $statusOnline
                        ],
                        'last_message' => $model,
                        'count_new_messages' => $countNewMessages
                    ];
                }

                $messages[] = $message;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $messages,
            'pagination' => [
                'pageSize' => (isset(Yii::$app->request->queryParams['per-page']))
                    ? Yii::$app->request->queryParams['per-page'] : 10
            ]
        ]);

        return $dataProvider;
    }

    /** Method of getting counts new messages user
     *
     * @param $recipientId
     * @return int
     */
    public function countNewMessagesByUser($recipientId): int
    {
        return (int) MessageEntity::find()
            ->where(['created_by' => \Yii::$app->user->identity->getId()])
            ->andWhere(['recipient_id' => $recipientId])
            ->andWhere(['status' => MessageEntity::STATUS_MESSAGE_UNREAD])
            ->count();
    }

    /**
     * Detailed message information action
     *
     * @return MessageEntity
     * @throws NotFoundHttpException
     * @param $id
     */
    public function getMessageDetail($id): MessageEntity
    {
        $message = self::findOne($id);
        if (!$message) {
            throw new NotFoundHttpException('Сообщение не найдено.');
        }

        return $message;
    }

    /**
     * Method of getting detailed message information after updating
     *
     * @param $id
     * @param $postData
     * @throws NotFoundHttpException
     * @return array
     */
    public function getUpdatedMessageDetail($id, $postData): array
    {
        /** @var  $message MessageEntity*/
        $message = self::findOne($id);
        $messageImages = [];
        if (!$message) {
            throw new NotFoundHttpException('Сообщение не найдено.');
        }

        if (isset($postData['images']['create'])) {
            $createImagesSrc = [];
            foreach ($postData['images']['create'] as $item) {
                $createImagesSrc[] = $item['src'];
            }

            $createMessageImages = $message->getImages()->select([
                'message_image.id', 'message_image.src'])->where(['in', 'src', $createImagesSrc])->all();
            foreach ($createMessageImages as $item) {
                $messageImages['images']['created'][] = [
                    'id'  => $item['id'],
                    'src' => $item['src']
                ];
            }
        }

        return array_merge($message->toArray(), $messageImages);
    }

    /**
     * Method of getting detailed message information after creating
     *
     * @return array
     * @throws NotFoundHttpException
     * @param $id
     */
    public function getCreatedMessageDetail($id): array
    {
        /** @var  $message MessageEntity*/
        $message = self::findOne($id);
        $messageImages = $message->getImages()->select(['message_image.id', 'message_image.src'])->all();

        $result = $message->getAttributes();
        foreach ($messageImages as $messageImage) {
            $result['images'][] = [
                'id'  => $messageImage['id'],
                'src' => $messageImage['src']
            ];
        }

        return $result;
    }

    /**
     * @return bool
     * @param $createdAt
     */
    public function acceptMessageUpdate($createdAt): bool
    {
        if (time() - $createdAt > Yii::$app->params['timeToMessageUpdate']) {
            return false;
        }

        return true;
    }
}