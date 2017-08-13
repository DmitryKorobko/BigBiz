<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use yii\base\Action;
use common\models\product\ProductEntity;

/**
 * Class ViewProductAction
 *
 * @package backend\modules\manage\administrator\controllers\actions\shop
 */
class ViewProductAction extends Action
{
    public $view = '@backend/modules/manage/users/views/shop/view_product';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'view-product';
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