<?php
namespace frontend\modules\main\controllers;

use yii\{
    filters\AccessControl, web\Controller
};
use frontend\modules\main\controllers\actions\{
    ThemeAction, MoreCategoryThemesAction
};

/**
 * Class CategoryController
 *
 * @package frontend\modules\main\controllers
 */
class CategoryController extends Controller
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
                            'theme',
                            'more-category-themes'
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
        $actions['theme'] = [
            'class' => ThemeAction::class,
        ];

        $actions['more-category-themes'] = [
            'class' => MoreCategoryThemesAction::class,
        ];

        return $actions;
    }

}