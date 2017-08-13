<?php

namespace rest\modules\api\v1\authorization\controllers\actions;

use common\behaviors\ValidatePostParameters;
use Yii;
use yii\{
    rest\Action, web\HttpException, web\Response, web\ServerErrorHttpException
};
use rest\models\RestUser;

/**
 * Class Send Security Code Action
 *
 * @mixin ValidatePostParameters
 *
 * @package rest\modules\api\v1\authorization\controllers\actions
 */
class SendSecurityCodeAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => ['email']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Action send security code for current user
     *
     * @return array
     * @throws HttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function run()
    {
        $email = Yii::$app->request->post('email');
        $recoveryCode = rand(1000, 9999);
        /* @var $user RestUser */
        $user = new RestUser();
        $user->validateUserEmail($email);
        $user = RestUser::findOne(['email' => $email]);
        $user->recovery_code = $recoveryCode;
        $user->created_recovery_code = time();

        /** Отправка письма с кодом на email пользователя */
        $result = Yii::$app->mailer->compose('@common/views/mail/sendSecurityCode-html.php',
            [ 'user' => $user, 'recoveryCode' => $recoveryCode ])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($email) // $email
            ->setSubject('Восстановление пароля')
            ->send();

        if ($result && $user->save(false)) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode('200', 'OK');
            $response->format = Response::FORMAT_JSON;
            return $response->content = [
                'status'  => $response->statusCode,
                'message' => 'Отправка кода восстановления прошло успешно'
            ];
        }
        throw new ServerErrorHttpException('Произошла ошибка при отправке кода восстановления!');
    }
}
