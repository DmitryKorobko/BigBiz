<?php
namespace backend\modules\authorization\controllers;

// todo заюзать php7
use backend\modules\authorization\controllers\actions\LoginAction;
use backend\modules\authorization\controllers\actions\LogoutAction;
use backend\modules\authorization\controllers\actions\RegistrationAction;
use yii\web\ErrorAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class AuthorizationController
 *
 * @package backend\modules\authorization\controllers
 */
class AuthorizationController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'registration',
                            'confirm',
                            'error'
                        ],
                        'allow'   => true
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['registration'] = [
            'class' => RegistrationAction::class,
        ];
        $actions['login'] = [
            'class' => LoginAction::class,
        ];
        $actions['logout'] = [
            'class' => LogoutAction::class,
        ];
        $actions['error'] = [
            'class' => ErrorAction::class,
        ];

        return $actions;
    }

}
