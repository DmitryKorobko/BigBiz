<?php
namespace backend\modules\shop\control\controllers\actions\product;

use common\models\product\ProductEntity;
use Yii;
use yii\base\Action;

/**
 * Class SetAvailabilityAction
 *
 * @package backend\modules\shop\control\controllers\actions\product
 */
class SetAvailabilityAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'set-availability';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var ProductEntity $product */
        $product = $this->controller->findModel($id);
        if ($product->setAvailability()) {
            if($product->getAvailability()) {
                Yii::$app->getSession()->setFlash('success', "Товар {$product->name} теперь доступен!");
                return $this->controller->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('success', "Товару {$product->name} присвоен статус - не активен!");
                return $this->controller->redirect('index');
            }
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла при изменении статуса товара - {$product->name} !");
            return $this->controller->redirect('index');
        }
    }
}