<?php
namespace backend\modules\shop\profile\controllers\actions;

use common\{
    models\shop_confidentiality\ShopConfidentialityEntity, behaviors\AccessShopBehavior
};
use Yii;
use yii\{
    base\Action, web\ServerErrorHttpException
};

/**
 * Class UpdateConfidentialityAction
 *
 * @mixin AccessShopBehavior
 * @package backend\modules\shop\profile\controllers\actions
 */
class UpdateConfidentialityAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update-confidentiality';
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
     * Action for updating confidentiality in "settings" tab
     *
     * @return string
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var  $model ShopConfidentialityEntity.php */
        $confidentiality = ShopConfidentialityEntity::findOne(['user_id' => @Yii::$app->user->id]);
        $confidentiality->setScenario(ShopConfidentialityEntity::SCENARIO_UPDATE);
        // todo не пойму зачем ты делаешь вот Yii::$app->request->post()['ShopConfidentialityEntity']. Можно же просто Yii::$app->request->post()
        $confidentiality->load(Yii::$app->request->post()['ShopConfidentialityEntity'], '');

        if ($confidentiality->save()) {
            Yii::$app->getSession()->setFlash('success', "Настройки успешно изменены!");
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при изменении настроек. 
            Повторите ещё раз или обратитесь к администрации сайта");
        }

        return $this->controller->redirect('index');
    }
}