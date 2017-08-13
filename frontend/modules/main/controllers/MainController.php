<?php
namespace frontend\modules\main\controllers;

use yii\{
    filters\AccessControl, web\Controller
};
use frontend\modules\main\controllers\actions\{
    IndexAction, StartAction, ConfAction, RegulationsAction, CategoryAction, ShopProfileAction
};

/**
 * Class MainController
 *
 * @package frontend\modules\main\controllers
 */
class MainController extends Controller
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
                            'index',
                            'start',
                            'conf',
                            'regulations',
                            'category',
                            'shop-profile'
                        ],
                        'allow'   => true,
                        'roles'   => [
                            '?',
                            'shop',
                            'admin',
                            'user',
                            'moder',
                            'quest'
                        ]
                    ]
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions['index'] = [
            'class' => IndexAction::class,
        ];
        $actions['start'] = [
            'class' => StartAction::class,
        ];
        $actions['conf'] = [
            'class' => ConfAction::class,
        ];
        $actions['regulations'] = [
            'class' => RegulationsAction::class,
        ];
        $actions['category'] = [
            'class' => CategoryAction::class,
        ];
        $actions['shop-profile'] = [
            'class' => ShopProfileAction::class,
        ];

        return $actions;
    }
}