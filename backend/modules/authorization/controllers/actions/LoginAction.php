<?php
namespace backend\modules\authorization\controllers\actions;

use backend\modules\authorization\models\{
    RegistrationForm, LoginForm
};
use Yii;
use yii\base\Action;

/**
 * Class LoginAction
 *
 * @package backend\modules\authorization\controllers\actions
 */
class LoginAction extends Action
{
    public $view = '@backend/modules/authorization/views/authorization/login';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'login';
    }

    /**
     * Action login implements login to admin panel and if authorized user
     * try to out from admin panel page to login/registration page he will
     * redirected back to admin/shop homepage
     *
     * @return string|\yii\web\Response
     */
    public function run()
    {
        if (!\Yii::$app->user->isGuest) {
            if (Yii::$app->user->can('shop')) {
                return $this->controller->redirect('/admin/shop/home/dashboard');
            }

            if (Yii::$app->user->can('admin')) {
                return $this->controller->redirect('/admin/manage/home/dashboard');
            }

            if (Yii::$app->user->can('moder')) {
                return $this->controller->redirect('/admin/moderator/home/dashboard');
            }
        }

        /** @var  $modelLogin LoginForm.php */
        $modelLogin = new LoginForm();
        /** @var  $model RegistrationForm.php */
        $modelRegistration = new RegistrationForm();
        if ($modelLogin->load(Yii::$app->request->post()) && $modelLogin->login()) {
            if (Yii::$app->user->can('shop')) {
                return $this->controller->redirect('/admin/shop/home/dashboard');
            }

            if (Yii::$app->user->can('admin')) {
                return $this->controller->redirect('/admin/manage/home/dashboard');
            }

            if (Yii::$app->user->can('moder')) {
                return $this->controller->redirect('/admin/moderator/home/dashboard');
            }
        } else {
            return $this->controller->render($this->view, ['modelLogin' => $modelLogin, 'modelRegistration' => $modelRegistration]);
        }
    }
}