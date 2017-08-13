<?php
namespace backend\modules\shop\control\controllers\actions\product;

use common\models\product\ProductEntity;
use Yii;
use yii\base\Action;

/**
 * Class IndexAction
 *
 * @package backend\modules\shop\control\controllers\actions\product
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/shop/control/views/product/index';

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
        $searchModel = new ProductEntity();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view, [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}