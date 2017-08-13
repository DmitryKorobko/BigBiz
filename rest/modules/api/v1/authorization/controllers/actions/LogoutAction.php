<?php

namespace rest\modules\api\v1\authorization\controllers\actions;

use yii\rest\Action;
use yii\web\{HttpException, ServerErrorHttpException};
use rest\models\RestUser;
use Yii;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class Logout Action
 *
 * @package rest\modules\api\v1\authorization\controllers\actions
 */
class LogoutAction extends Action
{
    /**
     * Session closed response code
     */
    const SESSION_CLOSED_CODE = 15;

    /**
     * Token
     *
     * @var null
     */
    public $authorizationToken = null;

    /**
     * Logout user action
     *
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $user = RestUser::findIdentityByAccessToken($this->authorizationToken, HttpBearerAuth::class);
        if (RestUser::addBlackListToken($this->authorizationToken)) {
            $user->status_online = 0;
            if ($user->save()) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode('200', 'OK');
                return $response->content = [
                    'status'  => $response->statusCode,
                    'code'    => self::SESSION_CLOSED_CODE,
                    'message' => 'Сессия успешно закрыта.'
                ];
            }
        }

        throw new ServerErrorHttpException;
    }
}
