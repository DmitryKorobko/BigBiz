<?php
namespace rest\modules\api\v1\shop\controllers;

use common\models\{
    shop_feedback\ShopFeedbackEntity, shop_profile\ShopProfileEntity
};
use rest\modules\api\v1\shop\controllers\actions\review\{
    AddShopReviewAction, ReviewDetailAction, ReviewsAction, UpdateShopReviewAction, RatingAction
};
use yii\{
    filters\auth\HttpBearerAuth, rest\ActiveController
};

/**
 * Class ReviewController
 *
 * @package rest\modules\api\v1\shop\controllers
 */
class ReviewController extends ActiveController
{
    /** @var  $modelClass ShopFeedbackEntity.php */
    public $modelClass = ShopFeedbackEntity::class;

    /**
     * @var array
     */
    public $serializer = [
        'class'              => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['reviews'] = [
            'class'      => ReviewsAction::class,
            'modelClass' => ShopProfileEntity::class
        ];

        $actions['add-shop-review'] = [
            'class'      => AddShopReviewAction::class,
            'modelClass' => ShopFeedbackEntity::class
        ];

        $actions['update-shop-review'] = [
            'class'      => UpdateShopReviewAction::class,
            'modelClass' => ShopFeedbackEntity::class
        ];

        $actions['review-detail'] = [
            'class'      => ReviewDetailAction::class,
            'modelClass' => ShopFeedbackEntity::class
        ];

        $actions['rating'] = [
            'class'      => RatingAction::class,
            'modelClass' => ShopProfileEntity::class
        ];

        return $actions;
    }
}
