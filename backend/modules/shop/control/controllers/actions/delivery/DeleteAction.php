<?php
namespace backend\modules\shop\control\controllers\actions\delivery;

use common\models\product\ProductEntity;
use common\models\product_delivery\ProductDeliveryEntity;
use Yii;
use yii\base\Action;

/**
 * Class DeleteAction
 *
 * @package backend\modules\shop\control\controllers\actions\delivery
 */
class DeleteAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete';
    }

    /**
     * Action for deleting delivery product
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var $delivery ProductDeliveryEntity.php */
        $delivery = ProductDeliveryEntity::findOne($id);
        $productName = ProductEntity::findOne(['id' => $delivery->product_id])->name;

        if ($delivery->delete()) {
            Yii::$app->getSession()->setFlash('success', "Адрес доставки для товара {$productName} успешно!");
            return $this->controller->redirect(['index']);
        }


        Yii::$app->getSession()->setFlash('error',
            "Произошла ошибка. Попробуйте еще раз или обратитесь в поддержку!");
        return $this->controller->redirect(['index']);
    }
}