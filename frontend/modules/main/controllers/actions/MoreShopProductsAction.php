<?php
namespace frontend\modules\main\controllers\actions;

use common\models\product\ProductEntity;
use yii\{
    base\Action, helpers\BaseJson
};
use Yii;
use stdClass;

/**
 * Class MoreShopProductsAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class MoreShopProductsAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'more-shop-products';
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var  $product ProductEntity*/
        $product = new ProductEntity();
        $shop  = Yii::$app->request->post()['shop'];
        $limit = Yii::$app->request->post()['limit'];

        $result = new stdClass();
        $result->limit = $limit + Yii::$app->params['productsPerPage'];
        $result->products = $product->getProducts($shop,false, true, $limit);

        return BaseJson::encode($result);
    }
}