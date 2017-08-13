<?php

namespace rest\modules\api\v1\authorization\controllers\actions;

use common\behaviors\ValidatePostParameters;
use Yii;
use yii\{
    base\Exception, rest\Action, web\HttpException, web\NotFoundHttpException, web\Response
};
use rest\models\RestUser;

/**
 * Class Recovery Password Action
 *
 * @mixin ValidatePostParameters
 * @package rest\modules\api\v1\authorization\controllers\actions\user
 */
class RecoveryPasswordAction extends Action
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
                'inputParams' => ['email', 'password_hash', 'confirm', 'recovery_code']
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
     * Recovery Password Action
     *
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run()
    {
        $email = Yii::$app->request->post('email');

        /* @var $user RestUser */
        $user = new RestUser();
        $user->validateUserEmail($email);
        $user = RestUser::findOne(['email' => $email]);
        $user->scenario = RestUser::SCENARIO_RECOVERY_PWD;
        try {
            if ($user->recoveryCode(Yii::$app->request->post())) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode('200', 'OK');
                $response->format = Response::FORMAT_JSON;
                return $response->content = [
                    'status'  => $response->statusCode,
                    'message' => 'Восстановления пароля прошло успешно'
                ];
            }
        } catch (Exception $e) {
            throw new HttpException(422, $e->getMessage());
        }
    }
}
