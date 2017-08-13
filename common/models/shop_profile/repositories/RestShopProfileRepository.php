<?php
namespace common\models\shop_profile\repositories;

use Yii;
use yii\{
    data\ArrayDataProvider, helpers\ArrayHelper
};
use common\models\{
    answer\AnswerEntity, message\MessageEntity, product\ProductEntity, shop_feedback\ShopFeedbackEntity,
    shop_profile\ShopProfileEntity, theme\ThemeEntity, user\UserEntity, user\repositories\UserRepository,
    user_profile\UserProfileEntity, admin_contact\AdminContactEntity
};

/**
 * Class RestShopProfileRepository
 *
 * @package common\models\shop_profile\repositories
 */
trait RestShopProfileRepository
{
    use UserRepository;
    /**
     * Method of getting list of shops.
     *
     * @param $params
     * @return ArrayDataProvider
     */
    public function getListShops($params): ArrayDataProvider
    {
        /** @var  $shopFeedback ShopFeedbackEntity.php */
        $shopFeedback = new ShopFeedbackEntity();

        $query = ShopProfileEntity::find()
            ->select(['shop_profile.id', 'shop_profile.name', 'shop_profile.image', 'shop_profile.status_text',
                'shop_profile.user_id'])
            ->joinWith('cities')
            ->joinWith('user')
            ->where([
                'user.status' => UserEntity::STATUS_VERIFIED
            ])
            ->andWhere(['>', 'shop_profile.category_end', time()])
            ->andWhere(['<', 'shop_profile.category_start', time()]);

        if (!empty($params) && isset($params['name'])) {
            $query->andFilterWhere(['like', 'shop_profile.name', $params['name']]);
        }

        if (!empty($params) && isset($params['city'])) {
            $query->andFilterWhere(['like', 'profile_city.city_id', $params['city']]);
        }

        $result = $query->asArray()->all();
        $shops = [];
        foreach ($result as $key => $value) {
            $rating = $shopFeedback->getAverageShopRating($value['user']['id']);
            $value['rating'] = isset($rating['average_rating']) ? $rating['average_rating'] : 0;
            $value['is_online'] = UserRepository::isOnline($value['user']['id']);
            $value['id'] = (int) $value['id'];
            $value['user_id'] = (int) $value['user_id'];

            unset($value['user']);

            $shops[] = $value;
        }
        ArrayHelper::multisort($shops, ['rating'], [SORT_DESC]);

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $shops,
            'pagination' => [
                'pageSize' => isset($params['per-page']) ? $params['per-page'] : 10
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Method of getting shop profile information by userId.
     *
     * @param $userId
     * @return array
     */
    public function findProfile($userId): array
    {
        /** @var  $shopFeedback ShopFeedbackEntity.php */
        $shopFeedback = new ShopFeedbackEntity();

        $shopProfile = ShopProfileEntity::find()
            ->select(['shop_profile.id', 'shop_profile.name', 'shop_profile.status_text', 'shop_profile.image',
                'shop_profile.user_id','user.created_at as user_registration_date', 'shop_profile.name',
                'shop_profile.work_start_time', 'shop_profile.work_end_time', 'shop_profile.skype',
                'shop_profile.viber', 'shop_profile.telegram', 'shop_profile.jabber', 'shop_profile.vipole'])
            ->joinWith('user')
            ->joinWith('cities')
            ->where([
                'shop_profile.user_id' => $userId
            ])
            ->one();
;
        if ($shopProfile) {
            $cities = $shopProfile['cities'];
            $rating = $shopFeedback->getAverageShopRating($shopProfile['user']['id']);
            $userRegistration = $shopProfile['user']['created_at'];

            unset($shopProfile['cities']);
            unset($shopProfile['user']);

            $shopProfile = $shopProfile->toArray();
            $shopProfile['user_registration_date'] = $userRegistration;
            $shopProfile['rating'] = isset($rating['average_rating']) ? $rating['average_rating'] : 0;
            $shopProfile['count_themes']   = (int) ThemeEntity::find()->where(['user_id' => $userId])->count();
            $shopProfile['count_products'] = (int) ProductEntity::find()->where(['user_id' => $userId])->count();
            $shopProfile['count_reviews']  = (int) ShopFeedbackEntity::find()->where(['shop_id' => $userId])->count();
            $shopProfile['is_online'] = UserRepository::isOnline($userId);

            return $shopProfile = array_merge($shopProfile, ['cities' => $cities]);
        }
        return [];
    }

    /**
     * Method of getting preview shop information with themes, products, rating.
     *
     * @param $userId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPreviewInformation($userId)
    {
        $theme = new ThemeEntity();
        $product = new ProductEntity();
        /** @var  $shopFeedback ShopFeedbackEntity.php */
        $shopFeedback = new ShopFeedbackEntity();

        $query = ShopProfileEntity::find()
            ->select(['shop_profile.id', 'shop_profile.name', 'shop_profile.image', 'shop_profile.user_id',
                'user.created_at as user_registration_date'])
            ->joinWith('user')
            ->where([
                'shop_profile.user_id' => $userId
            ]);
        
        $shopProfile = $query->one();
        if ($shopProfile) {
            $rating = $shopFeedback->getAverageShopRating($shopProfile['user']['id']);
            $userRegistrationDate = $shopProfile->user->created_at;

            $shopProfile = $shopProfile->toArray();
            $shopProfile['user_registration_date'] = $userRegistrationDate;
            $shopProfile['rating'] = isset($rating['average_rating']) ? $rating['average_rating'] : 0;
            $shopProfile['is_online'] = UserRepository::isOnline($userId);
            $shopProfile['themes']   = $theme->getListShopThemes($userId, 10);
            $shopProfile['count_themes'] = $theme->getCountShopThemes($userId);
            $shopProfile['products'] = $product->getProducts($userId, 10);
            $shopProfile['count_products'] = $product->getCountShopProducts($userId);

            unset($shopProfile['user']);

            return $shopProfile;
        }

        return [];
    }

    /**
     * Method of getting reviews about shop.
     *
     * @param $params
     * @return array|ArrayDataProvider
     */
    public function getShopReviews($params): ArrayDataProvider
    {
        $query = ShopFeedbackEntity::find()
            ->select(['shop_feedback.id', 'shop_feedback.created_by as user_id', 'user_profile.nickname as user_name',
                'shop_feedback.product_rating', 'shop_feedback.operator_rating', 'shop_feedback.reliability_rating',
                'shop_feedback.marker_rating', 'shop_feedback.average_rating', 'shop_feedback.created_at'])
            ->leftJoin('user_profile', 'user_profile.user_id = shop_feedback.created_by')
            ->leftJoin('shop_profile', 'shop_profile.user_id = shop_feedback.created_by')
            ->where(['shop_feedback.shop_id' => $params['shop_id']])
            ->orderBy(['shop_feedback.created_at' => 'desc']);

        $models = $query->asArray()->all();

        $reviews = [];

        if ($models) {
            foreach ($models as $review) {
                $reviewWithoutUserName = [
                    'id'                 => (int) $review['id'],
                    'product_rating'     => (int) $review['product_rating'],
                    'operator_rating'    => (int) $review['operator_rating'],
                    'reliability_rating' => (int) $review['reliability_rating'],
                    'marker_rating'      => (int) $review['marker_rating'],
                    'average_rating'     => (int) $review['average_rating'],
                    'created_at'         => (int) $review['created_at'],
                ];

                /**
                 * @var  $shop ShopProfileEntity
                 * @var  $user UserProfileEntity
                 */
                $shop = ShopProfileEntity::findOne(['user_id' => $review['user_id']]);
                $user = UserProfileEntity::findOne(['user_id' => $review['user_id']]);

                if ($shop) {
                    $reviewWithoutUserName['created_by'] = [
                        'name'      => $shop->name,
                        'avatar'    => $shop->image,
                        'is_online' => UserRepository::isOnline($review['user_id'])
                    ];
                } elseif ($user) {
                    $reviewWithoutUserName['created_by'] = [
                        'name'      => $user->nickname,
                        'avatar'    => $user->avatar,
                        'is_online' => UserRepository::isOnline($review['user_id'])
                    ];
                } else {
                    $reviewWithoutUserName['created_by'] = [
                        'name'          => AdminContactEntity::getCurrentName(),
                        'avatar'        => AdminContactEntity::getCurrentImage($review['user_id']),
                        'status_online' => UserRepository::isOnline($review['user_id'])
                    ];
                }

                $reviews[] = $reviewWithoutUserName;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $reviews,
            'pagination' => [
                'pageSize' => (isset($params['per-page'])) ? $params['per-page'] : 10
            ]
        ]);
        return $dataProvider;
    }

    /**
     * Method of getting side menu of shop.
     *
     * @param $shopId
     * @return array
     */
    public function getShopSideMenu($shopId): array
    {
        $review = new ShopFeedbackEntity();
        $answer = new AnswerEntity();
        $message = new MessageEntity();
        $shopProfile = ShopProfileEntity::find()
            ->select(['shop_profile.user_id', 'shop_profile.name', 'shop_profile.image'])
            ->where(['shop_profile.user_id' => $shopId])
            ->asArray()
            ->one();

        $shopProfile = [
            'shop_id'            => (int) $shopProfile['user_id'],
            'name'               => $shopProfile['name'],
            'image'              => $shopProfile['image'],
            'count_new_messages' => $message->getCountNewMessagesByCurrentUser(),
            'count_new_answers'  => $answer->getCountNewAnswers(),
            'count_new_reviews'  => $review->getCountNewReviews()
        ];

        return $shopProfile;
    }
}