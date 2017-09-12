<?php
namespace common\models\product\repositories;

use common\models\{
    product\ProductEntity, product_feedback\ProductFeedbackEntity, settings\SettingsEntity
};
use common\models\user_product_favorite\UserProductFavoriteEntity;
use Yii;
use yii\{
    data\ArrayDataProvider, helpers\ArrayHelper, web\NotFoundHttpException
};

/**
 * Class RestProductRepository
 *
 * @package common\models\product\repositories
 */
trait RestProductRepository
{
    /**
     * Method of getting minimal count of favorite product
     *
     * @return int
     */
    public function getMinimalFavoriteProductCount(): int
    {
        $minimalProductCount = ProductEntity::find()->select(['product.id', 'product_price.count as count'])
            ->leftJoin('user_product_favorite', 'user_product_favorite.product_id = product.id')
            ->leftJoin('product_price', 'product_price.product_id = product.id')
            ->where(['user_product_favorite.user_id' => Yii::$app->user->identity->getId()])
            ->min('product_price.count');

        return (int) $minimalProductCount;
    }

    /**
     * Method of getting products in favorites by userID
     *
     * @param bool $limit
     * @return array|ArrayDataProvider|\yii\db\ActiveRecord[]
     */
    public function getFavoritesProductsByUser($limit = false)
    {
        /** @var  $feedback ProductFeedbackEntity.php */
        $feedback = new ProductFeedbackEntity();

        $query = ProductEntity::find()->select(['product.id', 'product.name', 'product.image',
            'product.availability', 'product_price.price as price'])
            ->leftJoin('user_product_favorite', 'user_product_favorite.product_id = product.id')
            ->leftJoin('product_price', 'product_price.product_id = product.id')
            ->where([
                'user_product_favorite.user_id' => Yii::$app->user->identity->getId(),
            ]);

        if ($limit) {
            $models = $query->limit($limit)->asArray()->all();
        } else {
            $models = $query->asArray()->all();
        }

        $products = [];
        if ($models) {
            foreach ($models as $product) {
                $product = [
                    'id'             => (int) $product['id'],
                    'name'           => $product['name'],
                    'image'          => (!empty($product['image'])) ? $product['image']
                        : '/admin/images/default/no_image.png',
                    'average_rating' => (int) $feedback->getAverageProductRating($product['id']),
                    'count_report'   => (int) $feedback->getProductFeedbackCount($product['id']),
                    'availability'   => (int) ($product['availability'] == 1) ? true : false,
                    'price'          => (double) $product['price'],
                    'is_favorite'    => $this->checkProductFavorite($product['id'])
                ];

                $products[] = $product;
            }
        }

        if ($limit) {
            return $products;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $products,
            'pagination' => [
                'pageSize' => Yii::$app->request->getQueryParam('per-page')
                    ?? SettingsEntity::findOne(['key' => 'productsPerPage'])->value ?? Yii::$app->params['productsPerPage']
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Method of getting total count product feedback
     *
     * @param $shopId
     * @return mixed
     */
    public function getTotalCountProductFeedback($shopId)
    {
        $ids = ArrayHelper::getColumn(ProductEntity::find()->select('id')
            ->where(['user_id' => $shopId])->asArray()->all(), 'id');

        return ProductFeedbackEntity::find()->where(['in', 'product_id', $ids])->count();
    }

    /**
     * Method of getting product detail information
     *
     * @param $params
     * @throws NotFoundHttpException
     * @return ProductEntity
     */
    public function getProductDetails($params)
    {
        /** @var  $feedback ProductFeedbackEntity.php */
        $feedback = new ProductFeedbackEntity();

        /** @var  $product ProductEntity*/
        $product = ProductEntity::find()
            ->select(['product.id', 'product.name', 'product.description', 'product.image',
                'product.availability', 'product.created_at', 'product.updated_at', 'product.user_id'])
            ->joinWith('prices')
            ->joinWith('cities')
            ->where(['product.id' => $params['id']])
            ->one();

        if (!$product) {
            throw new NotFoundHttpException('Товар не найден');
        }

        $prices = $product['prices'];
        $cities = $product['cities'];

        $product = $product->toArray();
        $product['is_favorite'] = $this->isFavoriteProductByUser($params['id'], Yii::$app->user->identity->getId());
        $product['count_reports']  = (int) ProductFeedbackEntity::find()->where(['product_id' => $params['id']])->count();
        $product['average_rating'] = $feedback->getAverageProductRating($product['id']);

        return $product = array_merge($product, [
            'prices' => $prices,
            'cities' => $cities
        ]);
    }

    /** Method of checking the product in favorites
     *
     * @param $id
     * @return bool
     */
    public function checkProductFavorite($id): bool
    {
        if (UserProductFavoriteEntity::findOne(['product_id' => $id])) {
            return true;
        }

        return false;
    }

}