<?php
namespace backend\modules\shop\control\controllers\actions\delivery;

use common\models\product_delivery\ProductDeliveryEntity;
use yii\base\Action;
use Yii;

/**
 * Class IndexAction
 * @package backend\modules\shop\control\controllers\actions\delivery
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/shop/control/views/delivery/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function run()
    {
        /** @var  $searchModel ProductDeliveryEntity */
        $searchModel = new ProductDeliveryEntity();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}