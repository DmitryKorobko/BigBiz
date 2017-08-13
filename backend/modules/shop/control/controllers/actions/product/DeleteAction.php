<?php
namespace backend\modules\shop\control\controllers\actions\product;

use common\models\product\ProductEntity;
use Yii;
use yii\base\Action;
use yii\base\ErrorHandler;

/**
 * Class DeleteAction
 *
 * @package backend\modules\shop\control\controllers\actions\product
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
     * Action for deleting product with images
     *
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     */
    public function run($id)
    {
        /** @var ProductEntity $deletedProduct */
        $deletedProduct = $this->controller->findModel($id);
        try {
            $deletedProduct->delete();
            Yii::$app->getSession()->setFlash('success', "Товар удален успешно!");
            return $this->controller->redirect(['index']);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка. Попробуйте еще раз или обратитесь в поддержку!");
            return $this->controller->redirect(['index']);
        }
    }
}