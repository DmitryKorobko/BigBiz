<?php
namespace rest\modules\api\v1\product\controllers;

use common\models\{
    product\ProductEntity, user_product_favorite\UserProductFavoriteEntity, product_like\ProductLikeEntity,
    product_feedback\ProductFeedbackEntity
};
use rest\modules\api\v1\product\controllers\actions\{
    AddProductReviewAction, DetailAction, ListReviewsAction, ListFavoritesAction,
    AddProductInFavoriteAction, DeleteProductFromFavoritesAction, ListAction, LikeAction, UpdateProductReviewAction
};
use yii\{
    filters\auth\HttpBearerAuth, rest\ActiveController
};

/**
 * Class ProductController
 *
 * @package rest\modules\api\v1\product\controllers
 */
class ProductController extends ActiveController
{
    /** @var  $modelClass ProductEntity.php */
    public $modelClass = ProductEntity::class;

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

        $actions['detail'] = [
            'class'      => DetailAction::class,
            'modelClass' => ProductEntity::class
        ];

        $actions['list-favorites'] = [
            'class'      => ListFavoritesAction::class,
            'modelClass' => ProductEntity::class
        ];

        $actions['add-favorite'] = [
            'class'      => AddProductInFavoriteAction::class,
            'modelClass' => UserProductFavoriteEntity::class
        ];

        $actions['delete-favorite'] = [
            'class'      => DeleteProductFromFavoritesAction::class,
            'modelClass' => UserProductFavoriteEntity::class
        ];

        $actions['list-reviews'] = [
            'class'      => ListReviewsAction::class,
            'modelClass' => ProductFeedbackEntity::class
        ];

        $actions['list'] = [
            'class'      => ListAction::class,
            'modelClass' => ProductEntity::class,
        ];

        $actions['like'] = [
            'class'      => LikeAction::class,
            'modelClass' => ProductLikeEntity::class,
        ];

        $actions['add-review'] = [
            'class'      => AddProductReviewAction::class,
            'modelClass' => ProductFeedbackEntity::class,
        ];

        $actions['update-review'] = [
            'class'      => UpdateProductReviewAction::class,
            'modelClass' => ProductFeedbackEntity::class,
        ];

        return $actions;
    }
}
