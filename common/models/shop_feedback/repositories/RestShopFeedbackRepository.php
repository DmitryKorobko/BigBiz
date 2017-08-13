<?php
namespace common\models\shop_feedback\repositories;

use yii\web\NotFoundHttpException;
use common\models\shop_feedback\ShopFeedbackEntity;

/**
 * Class RestShopFeedbackRepository
 *
 * @package common\models\shop_feedback\repositories
 */
trait RestShopFeedbackRepository
{
    /**
     * Method of getting review information
     *
     * @param $shopId
     * @param $createdBy
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function getReviewDetail($shopId, $createdBy)
    {
        $review = ShopFeedbackEntity::find()
            ->where(['created_by' => $createdBy, 'shop_id' => $shopId])
            ->one();

        if (!$review) {
            throw new NotFoundHttpException('Отзыв не найден.');
        }

        return $review->toArray();
    }
}