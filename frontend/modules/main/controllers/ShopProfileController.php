<?php
namespace frontend\modules\main\controllers;

use yii\{
    filters\AccessControl, web\Controller
};
use frontend\modules\main\controllers\actions\{
    MoreShopProductsAction, ThemeAction, MoreShopThemesAction, MoreShopReviewsAction, NewShopReviewAction,
    ProductAction, MoreProductFeedbacksAction, NewProductFeedbackAction
};

/**
 * Class ShopProfileController
 *
 * @package frontend\modules\main\controllers
 */
class ShopProfileController extends Controller
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
                            'more-shop-themes',
                            'more-shop-products',
                            'more-shop-reviews',
                            'new-shop-review',
                            'product',
                            'more-product-feedbacks',
                            'new-product-feedback'
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

        $actions['more-shop-themes'] = [
            'class' => MoreShopThemesAction::class,
        ];

        $actions['more-shop-products'] = [
            'class' => MoreShopProductsAction::class,
        ];

        $actions['more-shop-reviews'] = [
            'class' => MoreShopReviewsAction::class,
        ];

        $actions['new-shop-review'] = [
            'class' => NewShopReviewAction::class,
        ];

        $actions['product'] = [
            'class' => ProductAction::class,
        ];

        $actions['more-product-feedbacks'] = [
            'class' => MoreProductFeedbacksAction::class,
        ];

        $actions['new-product-feedback'] = [
            'class' => NewProductFeedbackAction::class,
        ];

        return $actions;
    }

}