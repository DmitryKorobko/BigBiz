<?php
namespace backend\modules\authorization\controllers\actions;

use backend\modules\authorization\models\{
    RegistrationForm, LoginForm
};
use yii\base\Action;
use Yii;

/**
 * Class RegistrationAction
 *
 * @package backend\modules\authorization\controllers\actions
 */
class RegistrationAction extends Action
{
    public $viewRegistration = '@backend/modules/authorization/views/authorization/login';
    public $viewSuccess = '@backend/modules/authorization/views/authorization/success';
    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'registration';
    }
    /**
     * Action registration implements registration for "shop/user"-role users.
     * Validate input data and in success case send mail.
     *
     * @return string
     */
    public function run(): string
    {
        /** @var  $model RegistrationForm.php */
        $modelRegistration = new RegistrationForm();
        /** @var  $modelLogin LoginForm.php */
        $modelLogin = new LoginForm();

        $postData = Yii::$app->request->post();
        if (isset($postData['RegistrationForm']['termsConditions'])) {
            $postData['RegistrationForm']['termsConditions'] = 1;
        }

        if ($modelRegistration->load($postData) && $user = $modelRegistration->registration()) {
            $user->createFoldersForImages();
            Yii::$app->mailer->compose('sendVerificationCode-html', [
                'email'            => $modelRegistration->email,
                'verificationCode' => $modelRegistration->verificationCode
            ])->setFrom([Yii::$app->params['supportEmail'] => 'Регистрация'])
                ->setTo($modelRegistration->email)
                ->setSubject('Подтвердите регистрацию')
                ->send();
            return $this->controller->render($this->viewSuccess);
        } else {
            return $this->controller->render($this->viewRegistration,
                ['modelLogin' => $modelLogin, 'modelRegistration' => $modelRegistration]);
        }
    }
}