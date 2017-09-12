<?php
namespace backend\modules\shop\control\controllers\actions\delivery;

use common\models\{
    city\CityEntity, product\ProductEntity, product_price\ProductPriceEntity, product_delivery\ProductDeliveryEntity
};
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;

/**
 * Class CreateAction
 *
 * @package backend\modules\shop\control\controllers\actions\delivery
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/shop/control/views/delivery/create';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'create';
    }

    /**
     * Action for creating product
     *
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function run()
    {
        $postData = Yii::$app->request->getBodyParams();
        if ($postData) {
            $productName = ProductEntity::findOne(['id' => $postData['ProductDeliveryEntity']['product_id']])->name;
            /** @var  $productDelivery ProductDeliveryEntity */
            $productDelivery = new ProductDeliveryEntity();
            $productDelivery->load($postData['ProductDeliveryEntity'], '');
            if ($productDelivery->save()) {
                Yii::$app->getSession()->setFlash('success', "Адрес доставки для товара {$productName} добавлен успешно!");
                return $this->controller->redirect('index');
            }

            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка при добавлении адреса доставки товара - {$productName} !");
            return $this->controller->redirect('index');
        }
        $productIds = ArrayHelper::getColumn(ProductDeliveryEntity::find()->select(['product_id'])->asArray()->all(), 'product_id');

        return $this->controller->render($this->view, [
            'product'  => new ProductEntity(),
            'products' => ProductEntity::find()->where(['not in', 'id', $productIds])->asArray()->all(),
            'delivery' => new ProductDeliveryEntity()
        ]);
    }
}