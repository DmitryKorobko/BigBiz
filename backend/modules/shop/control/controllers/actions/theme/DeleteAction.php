<?php
namespace backend\modules\shop\control\controllers\actions\theme;

use common\models\theme\ThemeEntity;
use Yii;
use yii\base\Action;
use yii\web\ErrorHandler;

/**
 * Class DeleteAction
 *
 * @package backend\modules\shop\control\controllers\actions\theme
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
     * Delete a choosen theme by id.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function run($id)
    {
        /** @var $theme ThemeEntity.php */
        $theme = $this->controller->findModel($id);
        try {
            $theme->delete();
            Yii::$app->getSession()->setFlash('success', "Тема удалена успешно!");
            return $this->controller->redirect(['index']);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error',
                "Произошла ошибка. Попробуйте еще раз или обратитесь в поддержку!");
            return $this->controller->redirect(['index']);
        }
    }
}