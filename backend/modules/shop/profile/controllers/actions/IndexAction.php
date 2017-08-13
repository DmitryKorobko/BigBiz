<?php
namespace backend\modules\shop\profile\controllers\actions;

use backend\models\BackendUserEntity;
use common\models\city\CityEntity;
use common\models\shop_feedback\ShopFeedbackEntity;
use common\models\shop_profile\ShopProfileEntity;
use Yii;
use yii\base\Action;
use yii\widgets\ActiveForm;

/**
 * Class IndexAction
 *
 * @package backend\modules\shop\profile\controllers\actions
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/shop/profile/views/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'index';
    }

    /**
     * Getting data from ShopProfileEntity model. Also implements ajax for
     * no-refresh validation and create/update functions
     *
     * @return string | array
     */
    public function run()
    {
        /* @var $user BackendUserEntity */
        $user = BackendUserEntity::findIdentity(@Yii::$app->user->id);

        /* @var $modelProfile ShopProfileEntity */
        $modelProfile = ShopProfileEntity::getProfile();

        /** @var  $shopFeedback ShopFeedbackEntity.php */
        $shopFeedback = new ShopFeedbackEntity();

        if ($modelProfile->load(Yii::$app->request->post())) {
            $cities = CityEntity::createMultipleModels(Yii::$app->request->post('ShopProfileEntity')['towns']);

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($modelProfile);
            }

            if ($modelProfile->validate() && $modelProfile->save()) {
                $modelProfile->linkAll('cities', $cities, [], true, true);
                Yii::$app->getSession()->setFlash('success', "Данные успешно обновлены!");
            }
        }

        return $this->controller->render($this->view, [
            'modelProfile'         => $modelProfile,
            'user'                 => $user,
            'rating'               => $shopFeedback->getAverageShopRating($user->id),
            'shopFeedback'         => $shopFeedback,
            'shopFeedbackProvider' => $shopFeedback->getListFeedBackByUserId(Yii::$app->request->queryParams, $user->id)
        ]);
    }
}