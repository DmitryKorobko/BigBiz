<?php
namespace backend\modules\shop\control\controllers\actions\product;

use yii\base\Action;
use common\models\product\ProductEntity;

/**
 * Class ViewAction
 *
 * @package backend\modules\shop\control\controllers\actions\product
 */
class ViewAction extends Action
{
    public $view = '@backend/modules/shop/control/views/product/view';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'view';
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function run($id):string
    {
        /** @var  $product ProductEntity*/
        $product = ProductEntity::findOne(['id' => $id]);
        $prices = $product->getListProductPrices($product->id);

        return $this->controller->render($this->view, [
            'product' => $product,
            'prices'  => $prices,
            'cities'  => $product->getListProductCities($product->id)
        ]);
    }
}