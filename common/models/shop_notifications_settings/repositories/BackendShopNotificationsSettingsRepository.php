<?php
namespace common\models\shop_notifications_settings\repositories;

use common\models\shop_notifications_settings\ShopNotificationsSettingsEntity;
use Yii;
use yii\{
    web\HttpException, web\ServerErrorHttpException
};

/**
 * Class BackendShopNotificationsSettingsRepository
 *
 * @package common\models\shop_notifications_settings\repositories
 */
trait BackendShopNotificationsSettingsRepository
{
    /**
     * Method of get shop notifications settings. Using REST API
     *
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function getShopNotificationsSettings(): array
    {
        $notificationsSettings = ShopNotificationsSettingsEntity::find()
            ->select([
                'new_personal_message',
                'new_review',
                'new_reply_comment',
                'new_product_report',
                'new_theme_comment',
                'theme_was_verified',
                'new_like',
                'messages_to_email',
                'site_dispatch'
            ])
            ->where(['user_id' => Yii::$app->user->identity->getId()])
            ->asArray()
            ->one();

        return $notificationsSettings;

    }

    /**
     * Method for create shop profile notifications settings after registration
     *
     * @param $userId
     * @return bool
     */
    public function createShopProfileNotificationsSettings($userId): bool
    {
        $shopNotificationsSettings = $this;
        $shopNotificationsSettings->setScenario(ShopNotificationsSettingsEntity::SCENARIO_CREATE);
        $shopNotificationsSettings->setAttribute('user_id', $userId);

        return $shopNotificationsSettings->save();
    }
}