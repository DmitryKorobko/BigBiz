<?php
namespace backend\modules\shop\profile\controllers\actions;

use backend\models\BackendUserEntity;
use Yii;
use yii\base\Action;

/**
 * Class SendVerificationCodeAction
 *
 * @package backend\modules\shop\profile\controllers\actions
 */
class SendVerificationCodeAction extends Action
{
    public $view = '@backend/modules/shop/profile/views/index';

    /**
     * @return string
     */
    public static function getActionName()
    {
        return 'send-verification-code';
    }

    /**
     * @return string
     */
    public function run()
    {
        /* @var $user BackendUserEntity */
        $user = BackendUserEntity::findIdentity(@Yii::$app->user->id);
        $user->verification_code = $verificationCode = rand(1000, 9999);

        if ($user->save(false)) {
            $result = Yii::$app->mailer->compose('sendVerificationCode-html', [
                'email'            => $user->email,
                'verificationCode' => $user->verification_code
            ])->setFrom([Yii::$app->params['supportEmail'] => 'Регистрация'])
                ->setTo($user->email)
                ->setSubject('Подтвердите регистрацию')
                ->send();

            if ($result) {
                Yii::$app->getSession()->setFlash('success', 'Код верификации отправлен успешно!');
            } else {
                Yii::$app->getSession()->setFlash('error',
                    'Произошла ошибка при отправке кода верификации! Обратитесь к администрации сайта!');
            }
        } else {
            Yii::$app->getSession()->setFlash('error',
                'Произошла ошибка при отправке кода верификации! Обратитесь к администрации сайта!');
        }

        return $this->controller->redirect('index');
    }
}