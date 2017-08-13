<?php
namespace backend\modules\shop\profile\controllers\actions;

use common\{
    models\shop_notifications_settings\ShopNotificationsSettingsEntity, behaviors\AccessShopBehavior
};
use Yii;
use yii\{
    base\Action, web\ServerErrorHttpException
};

/**
 * Class UpdateNotificationsSettingsAction
 *
 * @mixin AccessShopBehavior
 * @package backend\modules\shop\profile\controllers\actions
 */
class UpdateNotificationsSettingsAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update-notifications-settings';
    }

    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'   => AccessShopBehavior::className(),
                'message' => 'Доступ запрещён'
            ]
        ];
    }

    /**
     * @return bool
     */
    public function beforeRun(): bool
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action for updating notifications settings in "settings" tab
     *
     * @return string
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var  $model ShopNotificationsSettingsEntity.php */
        $notifications = ShopNotificationsSettingsEntity::findOne(['user_id' => @Yii::$app->user->id]);
        $notifications->setScenario(ShopNotificationsSettingsEntity::SCENARIO_UPDATE);
        // todo аналогично
        $notifications->load(Yii::$app->request->post()['ShopNotificationsSettingsEntity'], '');

        if ($notifications->save()) {
            Yii::$app->getSession()->setFlash('success', "Настройки успешно изменены!");
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при изменении настроек. 
            Повторите ещё раз или обратитесь к администрации сайта");
        }

        return $this->controller->redirect('index');
    }
}