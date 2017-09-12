<?php
namespace common\models\message\repositories;

use common\models\message\MessageEntity;

/**
 * Class CommonMessageRepository
 *
 * @package common\models\message\repositories
 */
trait CommonMessageRepository
{

    /**
     * Method of getting count new messages for current authorized user
     *
     * @return int
     */
    public function getCountNewMessagesByCurrentUser(): int
    {
        return (int) MessageEntity::find()
            ->select('created_by')
            ->distinct('created_by')
            ->where(['recipient_id' => \Yii::$app->user->identity->getId(), 'status' => self::STATUS_MESSAGE_UNREAD])
            ->count();
    }
}