<?php
namespace common\models\shop_profile\repositories;

use common\models\{
    product\ProductEntity, shop_feedback\ShopFeedbackEntity, shop_profile\ShopProfileEntity, theme\ThemeEntity,
    user\UserEntity, profile_city\ProfileCityEntity
};

/**
 * Class FrontendShopProfileRepository
 *
 * @package common\models\shop_profile\repositories
 */
trait FrontendShopProfileRepository
{
    /**
     * Method of getting count of all shops.
     *
     * @return int
     */
    public function getCountAllShops(): int
    {
        return ShopProfileEntity::find()->count();
    }

    /**
     * Method of getting list of main information about shops.
     *
     * @return array
     */
    public function getListShopsWithMainInformation(): array
    {
        $model = ShopProfileEntity::find()
            ->select(['name', 'image', 'status_text', 'category_start', 'category_end', 'description', 'user_id',
                'user.status_online as status_online'])
            ->leftJoin('user', 'user.id = shop_profile.user_id')
            ->where(['user.status' => UserEntity::STATUS_VERIFIED])
            ->asArray()
            ->all();

        $shops = [];
        /** @var  $rating  ShopFeedbackEntity*/
        $rating = new ShopFeedbackEntity();
        /** @var  $product  ProductEntity*/
        $product = new ProductEntity();
        /** @var  $theme  ThemeEntity*/
        $theme = new ThemeEntity();
        $shopNumber = 0;

        foreach ($model as $shop) {
            if (($shop['category_start'] < time()) && ($shop['category_end'] > time())) {
                $shops[] = [
                    'image'             => $shop['image'],
                    'name'              => $shop['name'],
                    'status_text'       => $shop['status_text'],
                    'description'       => $shop['description'],
                    'status_online'     => $shop['status_online'],
                    'rating'            => $rating->getAverageShopRating($shop['user_id']),
                    'count_of_products' => $product->getCountShopProducts($shop['user_id']),
                    'count_of_themes'   => $theme->getCountShopThemes($shop['user_id']),
                    'shopNumber'        => ++$shopNumber,
                    'user_id'           => $shop['user_id']
                ];
            }
        }

        return $shops;
    }

    /**
     * Method of getting list of cities of shops.
     *
     * @param $userId
     * @return array
     */
    public function getListCitiesOfShop($userId): array
    {
        $cities = ProfileCityEntity::find()
            ->select(['city.name'])
            ->leftJoin('city', 'profile_city.city_id = city.id')
            ->where(['profile_city.profile_id' => $userId])
            ->asArray()
            ->all();

        return $cities;
    }

}