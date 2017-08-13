<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use common\models\shop_feedback\ShopFeedbackEntity;
use Yii;
use yii\base\Action;
use yii\base\ErrorHandler;

/**
 * Class DeleteShopFeedbackAction
 *
 * @package backend\modules\manage\users\controllers\actions\shop
 */
class DeleteShopFeedbackAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete-shop-feedback';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var $shopFeedback ShopFeedbackEntity.php */
        $shopFeedback = ShopFeedbackEntity::findOne(['id' => $id]);
        $userId = $shopFeedback->created_by;
        try {
            $shopFeedback->delete();
            Yii::$app->getSession()->setFlash('success', "Отзыв о магазине удален успешно!");
            return $this->controller->redirect('view?id=' . $userId);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при удалении отзывы о магазине! Смотреть логи!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}