<?php

namespace rest\modules\api\v1\authorization\controllers\actions;

use Yii;
use yii\rest\Action;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use rest\models\RestUser;
use yii\web\NotFoundHttpException;

/**
 * Class LoginGuest Action
 *
 * @package rest\modules\api\v1\authorization\controllers\actions
 */
class LoginGuestAction extends Action
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
    public static function getActionName()
    {
        return 'login-guest';
    }

    /**
     * Login guest action
     *
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run()
    {
        /** @var $model RestUser */
        $model = RestUser::findOne(['email' => Yii::$app->params['guest-email']]);
        if (empty($model)) {
            throw new NotFoundHttpException('Пользователь не найден. Пройдите этап регистрации.', self::USER_NOT_FOUND_CODE);
        }

        if (Yii::$app->getSecurity()->validatePassword(Yii::$app->params['guest-password'], $model->password_hash)) {
            $token = $model->getJWT();

            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => "Авторизация пользователя как 'гость' прошла успешно",
                'data'    => [
                    'token' => $token,
                    'exp'   => RestUser::getPayload($token, 'exp'),
                    'user'  => [
                        'id'         => $model->getId(),
                        'email'      => $model->email,
                        'role'       => RestUser::ROLE_GUEST,
                        'status'     => $model->getCurrentStatus($model->status),
                        'created_at' => $model->created_at
                    ]
                ]
            ];
        }

        throw new ForbiddenHttpException('Доступ запредещен.', self::FORBIDDEN_ACCESS_CODE);
    }
}
