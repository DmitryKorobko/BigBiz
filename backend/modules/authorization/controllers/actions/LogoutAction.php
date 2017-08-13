<?php
namespace backend\modules\authorization\controllers\actions;

use yii\base\Action;
use Yii;

/**
 * Class LogoutAction
 *
 * @package backend\modules\authorization\controllers\actions
 */
class LogoutAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'logout';
    }

    /**
     * Action login implements login to admin panel and if authorized user
     * try to out from admin panel page to login/registration page he will
     * redirected back to admin homepage
     *
     * @return string|\yii\web\Response
     */
    public function run()
    {
        Yii::$app->user->logout();

        return $this->controller->redirect(Yii::$app->homeUrl);
    }
}