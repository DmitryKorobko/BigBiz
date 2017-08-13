<?php
namespace common\models\user_confidentiality\repositories;

use common\models\user_confidentiality\UserConfidentialityEntity;
use Yii;
use yii\{
    web\HttpException, web\ServerErrorHttpException
};
use yii\helpers\ArrayHelper;

/**
 * Class RestUserConfidentialityRepository
 *
 * @package common\models\user_confidentiality\repositories
 */
trait RestUserConfidentialityRepository
{
    /**
     * Method of get user confidentiality. Using REST API
     *
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function getUserConfidentiality(): array
    {
        $userConfidentiality = UserConfidentialityEntity::find()
            ->select([
                'show_date_of_birth',
                'show_status_online',
                'view_page_access',
                'send_messages_access',
                'frequency_history_cleaning'
            ])
            ->where(['user_id' => Yii::$app->user->identity->getId()])
            ->one();

        return ($userConfidentiality) ? ArrayHelper::toArray($userConfidentiality) : [];
    }

    /**
     * Method for create user profile confidentiality after registration
     *
     * @param $userId
     * @return bool
     */
    public function createUserProfileConfidentiality($userId): bool
    {
        $userConfidentiality = $this;
        $userConfidentiality->setScenario(UserConfidentialityEntity::SCENARIO_CREATE);
        $userConfidentiality->setAttribute('user_id', $userId);

        return $userConfidentiality->save();
    }
}