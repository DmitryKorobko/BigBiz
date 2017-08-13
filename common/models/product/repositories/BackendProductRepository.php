<?php
namespace common\models\product\repositories;

use common\models\{
    product\ProductEntity, product_price\ProductPriceEntity, product_feedback\ProductFeedbackEntity
};
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class BackendProductRepository
 *
 * @package common\models\product\repositories
 */
trait BackendProductRepository
{
    /**
     * Method of getting list products with filters. Using for GridView
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $shopId = isset($params['shop_id']) ? $params['shop_id'] : Yii::$app->user->identity->getId();
        $query = ProductEntity::find()
            ->where(['user_id' => $shopId])
            ->orderBy('sort asc');

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => isset($params['limit']) ? $params['limit'] : 10
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name' => [
                    'asc'     => ['name' => SORT_ASC],
                    'desc'    => ['name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'cities',
                'category_by_shop.id',
                'availability',
                'created_at'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere('product.name LIKE "%' . $this->name . '%"');

        if (isset($params['ProductEntity']) && isset($params['ProductEntity']['cities'])
            && !empty($params['ProductEntity']['cities'])
        ) {
            $query->joinWith(['cities'])->andFilterWhere(['like', 'city.id', $params['ProductEntity']['cities']]);
        }

        if (!empty($this->product_created_range) && strpos($this->product_created_range, '-') !== false) {
            list($fromDate, $toDate) = explode(' - ', $this->product_created_range);
            $query->andFilterWhere(['between', 'created_at', strtotime($fromDate), strtotime($toDate)]);
        }

        if (isset($params['ProductEntity']['availability'])) {
            $query->andFilterWhere(['=', 'availability', $this->availability]);

        }

        return $dataProvider;
    }


    /**
     * Method of saving product prices
     *
     * @param [ProductPriceEntity] $modelPrice
     * @param array $pricesToDelete
     * @return boolean
     */
    public function savePrices($modelPrice, $pricesToDelete = null)
    {
        if (!empty($pricesToDelete)) {
            ProductPriceEntity::deleteAll(['id' => $pricesToDelete]);
        }
        $insert = [];
        /** @var ProductPriceEntity $price */
        foreach ($modelPrice as $price) {
            $insert[] = [
                'product_id' => $this->id,
                'count'      => $price->count,
                'price'      => $price->price,
                'price_usd'  => $price->price_usd
            ];
        }

        return Yii::$app->db->createCommand()
            ->batchInsert(ProductPriceEntity::tableName(), ['product_id', 'count', 'price', 'price_usd'], $insert)
            ->execute();
    }

    /**
     * Method of creating few models for saving many to many relationships
     *
     * @param $ids
     * @return array
     */
    public static function createMultipleModels($ids)
    {
        $result = [];
        if ($ids) {
            foreach ($ids as $id) {
                $result[] = ProductEntity::findOne($id);
            }
        }
        return $result;
    }

    /**
     * Method of getting most popular products
     *
     * @return array
     */
    public function getMostPopularProducts()
    {
        /** @var  $feedback ProductFeedbackEntity.php */
        $feedback = new ProductFeedbackEntity();

        $models = ProductEntity::find()->select(['product.id', 'product.name', 'product.image',
            'product.availability', 'product_price.price as price', 'product.description'])
            ->leftJoin('product_price', 'product_price.product_id = product.id')
            ->where(['product.user_id' => Yii::$app->user->identity->getId()])
            ->asArray()
            ->all();

        $products = [];
        if ($models) {
            foreach ($models as $product) {
                $product = [
                    'name'           => $product['name'],
                    'image'          => (!empty($product['image'])) ? $product['image']
                        : '/admin/images/default/no_image.png',
                    'average_rating' => $feedback->getAverageProductRating($product['id']),
                    'price'          => $this->getMinimalProductCountPrice($product['id']),
                    'description'    => $product['description']
                ];

                $products[] = $product;
            }

            foreach ($products as $key => $row) {
                $average_rating[$key]  = $row['average_rating'];
            }

            array_multisort($average_rating, SORT_DESC, $products);
            $products = array_slice($products, 0, 5);
        }

        return $products;
    }
}