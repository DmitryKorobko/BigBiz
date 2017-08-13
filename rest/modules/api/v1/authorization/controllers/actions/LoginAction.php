<?php

namespace rest\modules\api\v1\authorization\controllers\actions;

use Yii;
use yii\{
    rest\Action, web\ForbiddenHttpException, web\HttpException, web\NotFoundHttpException, web\ServerErrorHttpException
};
use rest\models\RestUser;

/**
 * Class Login Action
 *
 * @package rest\modules\api\v1\authorization\controllers\actions
 */
class LoginAction extends Action
{
    /**
     * Forbidden access response code
     */
    const FORBIDDEN_ACCESS_CODE = 11;

    /**
     * UserEntity not found response code
     */
    const USER_NOT_FOUND_CODE = 10;

    /**
     * @return string
     */
    public static function getActionName ()
    {
        return 'login';
    }

    /**
     * Login action
     *
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run()
    {
        /** @var $model RestUser */
        $model = RestUser::findOne(['email' => Yii::$app->getRequest()->getBodyParam('email')]);
        if (empty($model)) {
            throw new NotFoundHttpException('Пользователь не найден. Пройдите этап регистрации.',
                self::USER_NOT_FOUND_CODE);
        }

        $password = Yii::$app->getRequest()->getBodyParam('password_hash');

        if (!empty($password) && Yii::$app->getSecurity()->validatePassword($password, $model->password_hash)) {
            $refreshToken = base64_encode(md5(time()) . md5(rand(1000, 9999)));
            $token = $model->getJWT();

            $model->refresh_token = $refreshToken;
            if (!$model->save()) {
                throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке 
                    администарации приложения.');
            }

            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Авторизация прошла успешно',
                'data'    => [
                    'token'         => $token,
                    'refresh_token' => $refreshToken,
                    'exp'   => RestUser::getPayload($token, 'exp'),
                    'user'  => [
                        'id'         => $model->getId(),
                        'email'      => $model->email,
                        'role'       => $model->getUserRole($model->id),
                        'status'     => $model->getCurrentStatus($model->status),
                        'created_at' => $model->created_at
                    ]
                ]
            ];
        }

        throw new ForbiddenHttpException('Доступ запредещен.', self::FORBIDDEN_ACCESS_CODE);
    }
}
