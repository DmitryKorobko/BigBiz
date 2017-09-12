<?php
namespace common\models\product\repositories;

use common\models\{
    product\ProductEntity, product_price\ProductPriceEntity, product_feedback\ProductFeedbackEntity,
    user_product_favorite\UserProductFavoriteEntity, product_city\ProductCityEntity, city\CityEntity
};
use Yii;
use yii\data\ArrayDataProvider;

/**
 * Class CommonProductRepository
 *
 * @package common\models\product\repositories
 */
trait CommonProductRepository
{
    /**
     * Method of getting minimal count of product
     *
     * @param integer $productId Id of product
     * @return float
     */
    public function getMinimalProductCountPrice($productId)
    {
        $minimalProductCount = ProductEntity::find()->select(['product.id', 'product_price.count as count',
            'product_price.price as price'])
            ->leftJoin('product_price', 'product_price.product_id = product.id')
            ->where(['product.id' => $productId])
            ->min('product_price.count');

        $minimalProductCountPrice = ProductPriceEntity::find()->select(['price'])
            ->where(['product_price.product_id' => $productId, 'product_price.count' => $minimalProductCount])
            ->asArray()
            ->one();

        $price = $minimalProductCountPrice['price'];

        return $price;
    }

    /**
     * Method of getting list products by userID.
     *
     * @param bool $asArray If result must be array
     * @param integer $start Number of first element of result array in main array
     * @param integer $userId Id of user(shop)
     * @param bool $limit Limit of count of elements in result
     * @return array | ArrayDataProvider
     */
    public function getProducts($userId, $limit = false, $asArray = false, $start = null)
    {
        /** @var  $feedback ProductFeedbackEntity.php */
        $feedback = new ProductFeedbackEntity();

        $query = ProductEntity::find()
            ->select(['product.id', 'product.name', 'product.description', 'product.image', 'product.availability'])
            ->where(['user_id' => $userId])->joinWith('prices')->orderBy(['created_at' => 'desc']);

        if ($limit) {
            $models = $query->limit($limit)->asArray()->all();
        } else {
            $models = $query->asArray()->all();
        }

        $products = [];
        if ($models) {
            foreach ($models as $product) {
                $products[] = [
                    'id'             => (int) $product['id'],
                    'name'           => $product['name'],
                    'description'    => strip_tags($product['description']),
                    'image'          => (!empty($product['image'])) ? $product['image']
                        : '/admin/images/default/no_image.png',
                    'availability'   => (int) $product['availability'] ? true : false,
                    'is_favorite'    => $this->isFavoriteProductByUser($product['id'],
                        (!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId()
                            : 'quest'),
                    'count_report'   => (int) $feedback->getProductFeedbackCount($product['id']),
                    'average_rating' => $feedback->getAverageProductRating($product['id']),
                    'price'          => (float) $this->getMinimalProductCountPrice($product['id'])
                ];
            }
        }

        if ($limit) {

            return $products;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $products,
            'pagination' => [
                'pageSize' => isset(Yii::$app->request->queryParams['per-page'])
                    ? Yii::$app->request->queryParams['per-page'] : Yii::$app->params['productsPerPage']
            ]
        ]);

        if ($asArray) {

            return array_slice($products, $start, Yii::$app->params['productsPerPage']);
        }

        return $dataProvider;
    }

    /**
     * Check, product is favorite or not by userID
     *
     * @param integer $productId Id of product
     * @param integer $userId Id of user(shop)
     * @return bool
     */
    public function isFavoriteProductByUser($productId, $userId): bool
    {
        if (UserProductFavoriteEntity::find()->where(['product_id' => $productId, 'user_id' => $userId])->one()) {

            return true;
        }

        return false;
    }

    /**
     * Method of getting count of products of shop
     *
     * @param $userId
     * @return int
     */
    public function getCountShopProducts($userId): int
    {
        return ProductEntity::find()->where(['user_id' => $userId])->count();
    }

    /**
     * Method of getting list product prices
     *
     * @param integer $productId
     * @return array
     */
    public function getListProductPrices($productId) {
        $prices = ProductPriceEntity::find()
            ->select(['count', 'price'])
            ->where(['product_id' => $productId])
            ->asArray()
            ->all();

        /*For normally sorting by count (1, 2, 10, 100 instead 1, 10, 100, 2)*/
        foreach ($prices as $key => $row) {
            $count[$key]  = $row['count'];
            $price[$key]  = $row['price'];
        }

        array_multisort($count, SORT_ASC, $price, SORT_ASC, $prices);

        return $prices;
    }

    /**
     * Method of getting list product cities
     *
     * @param integer $productId
     * @return array
     */
    public function getListProductCities($productId) {
        $cities = CityEntity::find()
            ->select(['name'])
            ->leftJoin('product_city', 'product_city.city_id = city.id')
            ->where(['product_city.product_id' => $productId])
            ->orderBy(['city.name' => SORT_ASC])
            ->asArray()
            ->all();

        return $cities;
    }
}