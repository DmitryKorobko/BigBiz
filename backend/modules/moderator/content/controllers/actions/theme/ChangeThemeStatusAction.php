<?php
namespace backend\modules\moderator\content\controllers\actions\theme;

use common\models\theme\ThemeEntity;
use Yii;
use yii\base\Action;

/**
 * Class ChangeThemeStatusAction
 *
 * @package backend\modules\moderator\content\controllers\actions\theme
 */
class ChangeThemeStatusAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'change-theme-status';
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        $theme = ThemeEntity::findOne(['id' => $id]);
        $status = ($theme->status === ThemeEntity::STATUS_UNVERIFIED) ? ThemeEntity::STATUS_VERIFIED
            : ThemeEntity::STATUS_UNVERIFIED;

        if ($theme->changeThemeStatus($status)) {
            if ($status === ThemeEntity::STATUS_VERIFIED) {
                Yii::$app->getSession()->setFlash('success', "Тема успешно опубликована.");
                return $this->controller->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('success', "Тема успешно отключена.");
                return $this->controller->redirect('index');
            }
        } else {
            Yii::$app->getSession()->setFlash('error', "Произошла при изменении статуса темы!");
            return $this->controller->redirect('index');
        }
    }
}