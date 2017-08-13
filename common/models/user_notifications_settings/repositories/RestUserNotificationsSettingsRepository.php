<?php
namespace common\models\user_notifications_settings\repositories;

use common\models\user_notifications_settings\UserNotificationsSettingsEntity;
use Yii;
use yii\{
    web\HttpException, web\ServerErrorHttpException
};
use yii\helpers\ArrayHelper;

/**
 * Class RestUserNotificationsSettingsRepository
 *
 * @package common\models\user_notifications_settings\repositories
 */
trait RestUserNotificationsSettingsRepository
{
    /**
     * Method of get user notifications settings. Using REST API
     *
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function getUserNotificationsSettings(): array
    {
        $notificationsSettings = UserNotificationsSettingsEntity::find()
            ->select([
                'new_personal_message',
                'new_reputation',
                'new_reply_comment',
                'new_product_report',
                'new_theme_comment',
                'theme_was_verified',
                'new_like',
                'messages_to_email',
                'site_dispatch'
            ])
            ->where(['user_id' => Yii::$app->user->identity->getId()])
            ->one();

        return ($notificationsSettings) ? ArrayHelper::toArray($notificationsSettings) : [];
    }

    /**
     * Method for create user profile notifications settings after registration
     *
     * @param $userId
     * @return bool
     */
    public function createUserProfileNotificationsSettings($userId): bool
    {
        $userNotificationsSettings = $this;
        $userNotificationsSettings->setScenario(UserNotificationsSettingsEntity::SCENARIO_CREATE);
        $userNotificationsSettings->setAttribute('user_id', $userId);

        return $userNotificationsSettings->save();
    }
}