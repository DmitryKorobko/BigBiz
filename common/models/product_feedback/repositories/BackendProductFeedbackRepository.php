<?php
namespace common\models\product_feedback\repositories;

use common\models\product_feedback\ProductFeedbackEntity;
use yii\data\ActiveDataProvider;

/**
 * Class BackendProductFeedbackRepository
 *
 * @package common\models\product_feedback\repositories
 */
trait BackendProductFeedbackRepository
{

    /**
     * Method of getting count reviews of product by product Id
     *
     * @param $productId
     * @return int
     */
    public function getCountProductReviews($productId): int
    {
        $count = ProductFeedbackEntity::find()
            ->select(['id'])
            ->where(['id' => $productId])
            ->count();

        return $count;
    }

    /**
     * Method of getting list of reviews by creator ID
     *
     * @param $creatorId
     * @return ActiveDataProvider
     */
    public function getListFeedBackByCreatorId($creatorId): ActiveDataProvider
    {
        $query = ProductFeedbackEntity::find()->where(['user_id' => $creatorId]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ],
            ]
        ]);

        return $dataProvider;
    }
}