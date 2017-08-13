<?php
namespace common\models\shop_feedback\repositories;

use common\models\{
    shop_feedback\ShopFeedbackEntity, user\UserEntity
};
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Class BackendShopFeedbackRepository
 *
 * @package common\models\shop_feedback\repositories
 */
trait BackendShopFeedbackRepository
{

    /**
     * Method of getting list of reviews by userID
     *
     * @param $params
     * @param $userId
     * @return ActiveDataProvider
     */
    public function getListFeedBackByUserId($params, $userId): ActiveDataProvider
    {
        $verifiedUserIds = UserEntity::find()->select('id')->where(['status' => UserEntity::STATUS_VERIFIED]);
        $query = ShopFeedbackEntity::find()->where(['shop_id' => $userId, 'created_by' => $verifiedUserIds]);

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

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($params['ShopFeedbackEntity'])) {
            $query->andWhere(['created_by' => $this->created_by]);
        }

        return $dataProvider;
    }

    /**
     * Method of getting count reviews of shop
     *
     * @return int
     */
    public function getCountShopReviews(): int
    {
        $verifiedUserIds = UserEntity::find()->select('id')->where(['status' => UserEntity::STATUS_VERIFIED]);
        $count = ShopFeedbackEntity::find()
            ->select(['id'])
            ->where(['shop_id' => Yii::$app->user->identity->getId(), 'created_by' => $verifiedUserIds])
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
        $query = ShopFeedbackEntity::find()->where(['created_by' => $creatorId]);

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