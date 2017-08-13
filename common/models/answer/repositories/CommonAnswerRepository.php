<?php
namespace common\models\answer\repositories;

use common\models\answer\AnswerEntity;
/**
 * Class CommonAnswerRepository
 *
 * @package common\models\answer\repositories
 */
trait CommonAnswerRepository
{

    /**
     * Method of getting count new answers for user or shop
     *
     * @return int
     */
    public function getCountNewAnswers(): int
    {
        return AnswerEntity::find()
            ->where([
                'recipient_id' => \Yii::$app->user->identity->getId(),
                'status'       => self::STATUS_UNREAD
            ])
            ->count();
    }
}
