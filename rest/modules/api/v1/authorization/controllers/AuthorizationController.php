<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\{
    GenerationAccessTokenAction, LoginAction, LoginGuestAction, LogoutAction, RecoveryPasswordAction,
    RegisterAction, SendSecurityCodeAction
};
use rest\models\RestUser;
use yii\rest\ActiveController;

/**
 * Class AuthorizationController
 *
 * @package rest\modules\api\v1\authorization\controllers
 */
class AuthorizationController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = RestUser::class;

    /**
     * Token
     *
     * @var null
     */
    protected $authorizationToken = null;

    /**
     * AuthorizationController constructor.
     *
     * @param string $id
     * @param \yii\base\Module $module
     * @param RestUser $user
     * @param array $config
     */
    public function __construct($id, $module, RestUser $user, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->authorizationToken = $user->getAuthKey();
    }


    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['login'] = [
            'class'      => LoginAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['logout'] = [
            'class'              => LogoutAction::class,
            'modelClass'         => $this->modelClass,
            'authorizationToken' => $this->authorizationToken
        ];

        $actions['login-guest'] = [
            'class'              => LoginGuestAction::class,
            'modelClass'         => $this->modelClass
        ];

        $actions['register'] = [
            'class'      => RegisterAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['password-recovery'] = [
            'class'      => RecoveryPasswordAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['send-security-code'] = [
            'class'      => SendSecurityCodeAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['generation-access-token'] = [
            'class'      => GenerationAccessTokenAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
