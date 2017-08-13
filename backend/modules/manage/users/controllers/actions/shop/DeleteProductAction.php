<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use common\models\product\ProductEntity;
use Yii;
use yii\base\Action;
use yii\base\ErrorHandler;

/**
 * Class DeleteProductAction
 *
 * @package backend\modules\manage\users\controllers\actions\shop
 */
class DeleteProductAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete-product';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var ProductEntity $deletedProduct */
        $deletedProduct = ProductEntity::findOne(['id' => $id]);
        $userId = $deletedProduct->user_id;
        try {
            $deletedProduct->delete();
            Yii::$app->getSession()->setFlash('success', "Товар удален успешно!");
            return $this->controller->redirect('view?id=' . $userId);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при удалении продукта! Смотреть логи!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}