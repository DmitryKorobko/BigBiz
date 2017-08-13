<?php
namespace backend\modules\manage\users\controllers\actions\shop;

use common\models\feedback\Feedback;
use Yii;
use yii\base\Action;
use yii\base\ErrorHandler;

/**
 * Class DeleteFeedbackAction
 *
 * @package backend\modules\manage\users\controllers\actions\shop
 */
class DeleteFeedbackAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete-feedback';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var $feedback Feedback.php */
        $feedback = Feedback::findOne(['id' => $id]);
        $userId = $feedback->user_id;
        try {
            $feedback->delete();
            Yii::$app->getSession()->setFlash('success', "Запись удалена успешно!");
            return $this->controller->redirect('view?id=' . $userId);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при удалении! Смотреть логи!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}