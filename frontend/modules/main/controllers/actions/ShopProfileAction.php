<?php
namespace frontend\modules\main\controllers\actions;

use yii\{
    base\Action, data\ArrayDataProvider
};
use common\models\{
    product\ProductEntity, shop_profile\ShopProfileEntity, shop_feedback\ShopFeedbackEntity, theme\ThemeEntity,
    user\UserEntity
};
use Yii;

/**
 * Class ShopProfileAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class ShopProfileAction extends Action
{
    public $view = '@frontend/modules/main/views/shop_profile';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'shop-profile';
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function run($id):string
    {
        /** @var  $shop ShopProfileEntity*/
        $shop = ShopProfileEntity::findOne(['user_id' => $id]);
        /** @var  $abstractShop ShopProfileEntity*/
        $abstractShop = new ShopProfileEntity();
        /** @var  $user UserEntity*/
        $user = UserEntity::findOne(['id' => $id]);
        /** @var  $feedback ShopFeedbackEntity*/
        $review = new ShopFeedbackEntity();
        /** @var  $reviewDataProvider ArrayDataProvider*/
        $reviewDataProvider = $review->getListShopReviews(null, $shop['user_id'], true);
        $reviews = $reviewDataProvider->models;
        /** @var  $product ProductEntity*/
        $product = new ProductEntity();
        /** @var  $productDataProvider ArrayDataProvider*/
        $productDataProvider = $product->getProducts($shop['user_id']);
        $products = $productDataProvider->models;
        /** @var  $theme ThemeEntity*/
        $theme = new ThemeEntity();
        /** @var  $themeDataProvider ArrayDataProvider*/
        $themeDataProvider = $theme->getListThemesByCategoryOrShop(null, $shop['user_id']);
        $themes = $themeDataProvider->models;
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');

        return $this->controller->render($this->view, [
            'shop'                => $shop,
            'rating'              => $review->getAverageShopRating($shop['user_id']),
            'email'               => $user['email'],
            'cities'              => $abstractShop->getListCitiesOfShop($shop['user_id']),
            'statusOnline'        => $user->status_online,
            'reviewDataProvider'  => $reviewDataProvider,
            'reviews'             => $reviews,
            'reviewDataCount'     => $reviewDataProvider->count,
            'reviewAllDataCount'  => $reviewDataProvider->totalCount,
            'productDataProvider' => $productDataProvider,
            'products'            => $products,
            'productDataCount'    => $productDataProvider->count,
            'productAllDataCount' => $productDataProvider->totalCount,
            'themeDataProvider'   => $themeDataProvider,
            'themes'              => $themes,
            'themeDataCount'      => $themeDataProvider->count,
            'themeAllDataCount'   => $themeDataProvider->totalCount,
            'userId'              => $userId
        ]);
    }
}