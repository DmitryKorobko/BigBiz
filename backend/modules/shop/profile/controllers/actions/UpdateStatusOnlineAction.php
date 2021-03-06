<?php
namespace backend\modules\shop\profile\controllers\actions;

use common\{
    models\user\UserEntity, behaviors\AccessShopBehavior
};
use Yii;
use yii\{
    base\Action, web\ServerErrorHttpException
};

/**
 * Class UpdateStatusOnlineAction
 *
 * @mixin AccessShopBehavior
 * @package backend\modules\shop\profile\controllers\actions
 */
class UpdateStatusOnlineAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update-status-online';
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
     * Action for updating status online
     *
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var  $user UserEntity.php */
        $user = UserEntity::findOne(['id' => @Yii::$app->user->id]);
        $user->setScenario(UserEntity::SCENARIO_UPDATE);
        $user->setAttribute('status_online', Yii::$app->request->post()['status_online']);

        if ($user->save()) {
            return true;
        }

        return false;
    }
}