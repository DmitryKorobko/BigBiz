<?php

namespace rest\modules\api\v1\authorization\controllers\actions;

use common\behaviors\ValidatePostParameters;
use rest\models\RestUser;
use yii\rest\Action;
use Yii;
use yii\web\ {
    ServerErrorHttpException, HttpException, NotFoundHttpException
};
use yii\base\{
    ErrorHandler, Exception
};
use yii\db\Exception as ExceptionDb;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class GeneratorAccessTokenAction
 *
 * @mixin ValidatePostParameters
 * @package rest\modules\api\v1\authorization\controllers\actions
 */
class GenerationAccessTokenAction extends Action
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
                'inputParams' => [
                    'refresh_token'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Method of generating access token
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws HttpException
     * @return array
     */
    public function run(): array
    {
        $currentToken = (new $this->modelClass)->getAuthKey();
        $userId = RestUser::findIdentityByAccessToken($currentToken, HttpBearerAuth::class)->id;

        /** @var RestUser */
        $user = RestUser::findOne(['refresh_token' => Yii::$app->getRequest()->getBodyParams()['refresh_token'],
            'id' => $userId]);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь с таким токеном не найден.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->addBlackListToken($currentToken);
            $token = $user->getJWT();

            $transaction->commit();

            Yii::$app->getResponse()->setStatusCode(201, 'Created');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Токен успешно сгенерирован',
                'data'    => [
                    'access_token' => $token,
                    'refresh_token' => $user->refresh_token,
                    'exp' => RestUser::getPayload($token, 'exp'),
                    'user' => [
                        'id' => $user->getId(),
                        'email' => $user->email,
                        'role' => $user->role,
                        'status' => $user->getCurrentStatus($user->status),
                        'created_at' => $user->created_at
                    ]
                ]
            ];
        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при генерации нового токена.');
        }
    }
}
