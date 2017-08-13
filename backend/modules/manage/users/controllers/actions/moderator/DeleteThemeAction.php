<?php
namespace backend\modules\manage\users\controllers\actions\moderator;

use common\models\theme\ThemeEntity;
use Yii;
use yii\base\{
    Action, ErrorHandler
};

/**
 * Class DeleteThemeAction
 *
 * @package backend\modules\manage\users\controllers\actions\moderator
 */
class DeleteThemeAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'delete-theme';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        /** @var $theme ThemeEntity.php */
        $theme = ThemeEntity::findOne(['id' => $id]);
        $userId = $theme->user_id;
        try {
            $theme->delete();
            Yii::$app->getSession()->setFlash('success', "Тема удалена успешно!");
            return $this->controller->redirect('view?id=' . $userId);
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->getSession()->setFlash('error', "Произошла ошибка при удалении темы! Смотреть логи!");
            return $this->controller->redirect('view?id=' . $userId);
        }
    }
}