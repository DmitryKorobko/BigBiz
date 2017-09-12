<?php
namespace backend\modules\shop\support\controllers\actions;

use common\models\feedback\Feedback;
use yii\base\Action;
use yii\db\Exception;
use yii\web\ErrorHandler;
use Yii;

/**
 * Class CreateAction
 *
 * @package backend\modules\shop\support\controllers\actions
 */
class CreateAction extends Action
{
    public $view = '@backend/modules/shop/support/views/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'index';
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $feedback = new Feedback();

        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            $postData['Feedback']['user_id'] = \Yii::$app->user->identity->getId();

            if ($feedback->load($postData) && $feedback->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($feedback->save()) {
                        $transaction->commit();
                        Yii::$app->getSession()->setFlash('success', 'Сообщение отправлено успешно!');
                        return $this->controller->redirect('/admin/shop/support/send-message');
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    Yii::error(ErrorHandler::convertExceptionToString($e));
                    Yii::$app->getSession()->setFlash('error',
                        'Произошла ошибка при отправлении сообщения! Попробуйте еще раз!');
                    return $this->controller->redirect('/admin/shop/support/send-message');
                }
            }
        }

        return $this->controller->render($this->view, [
            'feedback' => $feedback,
        ]);
    }
}