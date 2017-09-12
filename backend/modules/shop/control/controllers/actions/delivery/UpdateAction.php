<?php
namespace backend\modules\shop\control\controllers\actions\delivery;

use common\models\{
    city\CityEntity, product\ProductEntity, product_price\ProductPriceEntity, product_delivery\ProductDeliveryEntity
};
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class UpdateAction
 *
 * @package backend\modules\shop\control\controllers\actions\delivery
 */
class UpdateAction extends Action
{
    public $view = '@backend/modules/shop/control/views/delivery/update';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'update';
    }

    /**
     * Action for updating product delivery
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function run($id)
    {
        /** @var  $productDelivery ProductDeliveryEntity */
        $productDelivery = ProductDeliveryEntity::findOne($id);
        $postData = Yii::$app->request->getBodyParams();
        if ($postData) {
            $productName = ProductEntity::findOne(['id' => $postData['ProductDeliveryEntity']['product_id']])->name;
            $productDelivery->load($postData['ProductDeliveryEntity'], '');
            if ($productDelivery->save()) {
                Yii::$app->getSession()->setFlash('success', "Адрес доставки для товара {$productName} обновлен успешно!");
                return $this->controller->redirect('index');
            }

            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка при обновлении адреса доставки товара - {$productName} !");
            return $this->controller->redirect('index');
        }

        return $this->controller->render($this->view, [
            'product'  => new ProductEntity(),
            'products' => ProductEntity::find()->asArray()->all(),
            'delivery' => $productDelivery
        ]);
    }
}