<?php
namespace backend\modules\manage\users\controllers\actions\customer;

use common\models\product_feedback\ProductFeedbackEntity;
use Yii;
use yii\base\{
    Action, ErrorHandler
};

/**
 * Class DeleteProductFeedbackAction
 *
 * @package backend\modules\manage\users\controllers\actions\customer
 */
class DeleteProductFeedbackAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete-product-feedback';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var $productFeedback ProductFeedbackEntity.php */
        $productFeedback = ProductFeedbackEntity::findOne(['id' => $id]);
        $userId = $productFeedback->user_id;
        try {
            $productFeedback->delete();
            Yii::$app->getSession()->setFlash('success', "Отзыв о товаре удален успешно!");
            return $this->controller->redirect('view?id=' . $userId);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при удалении отзывы о товаре!
             Смотреть логи!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}