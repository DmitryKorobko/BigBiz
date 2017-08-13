<?php
namespace common\models\shop_feedback\repositories;

use Yii;
use common\models\{
    user_profile\UserProfileEntity, shop_profile\ShopProfileEntity,
    shop_feedback\ShopFeedbackEntity, user\UserEntity
};
use yii\{
    base\ErrorHandler, base\Exception, web\HttpException, web\ServerErrorHttpException,
    db\Exception as ExceptionDb, data\ArrayDataProvider
};

/**
 * Class CommonShopFeedbackRepository
 *
 * @package common\models\shop_feedback\repositories
 */
trait CommonShopFeedbackRepository
{

    /**
     * Method of getting count new reviews for shop
     *
     * @return int
     */
    public function getCountNewReviews(): int
    {
        return ShopFeedbackEntity::find()
            ->where([
                'shop_id' => Yii::$app->user->identity->getId(),
                'status'       => ShopFeedbackEntity::STATUS_UNREAD
            ])
            ->count();
    }

    /**
     * Method of getting list of reviews by userID
     *
     * @param string $status Enum(READ, UNREAD) status of review
     * @param integer $userId Id of user
     * @param bool $asDataProvider If result must be as ArrayDataProvider
     * @param bool $forLoad If result using in load more function
     * @param integer $start Number of first element of result array in main array
     * @param bool $dateFormat If date in result must be ('d.m.y') format
     * @return array | ArrayDataProvider
     */
    public function getListShopReviews($status = null, $userId = null, $asDataProvider = false, $forLoad = false,
                                       $start = null, $dateFormat = false)
    {
        $query = ShopFeedbackEntity::find()
            ->select(['product_rating', 'operator_rating', 'reliability_rating', 'marker_rating', 'average_rating',
                'created_by', 'shop_feedback.created_at', 'shop_profile.name as shop_name',
                'user.status_online as status_online'])
            ->leftJoin('shop_profile', 'shop_profile.user_id = shop_feedback.shop_id')
            ->leftJoin('user', 'user.id = shop_feedback.created_by');

        if (!empty($userId)) {
            $query->where(['shop_id' => $userId]);
        } else {
            $query->where(['shop_id' => Yii::$app->user->identity->getId()]);
        }

        if (!empty($status)) {
            $query->andWhere(['shop_feedback.status' => $status]);
        }

        $models = $query->orderBy(['shop_feedback.created_at' => SORT_DESC])->asArray()->all();

        $reviews = [];
        if ($models) {
            foreach ($models as $review) {
                $reviewWithoutUserName = [
                    'product_rating'     => $review['product_rating'],
                    'operator_rating'    => $review['operator_rating'],
                    'reliability_rating' => $review['reliability_rating'],
                    'marker_rating'      => $review['marker_rating'],
                    'average_rating'     => $review['average_rating'],
                    'created_at'         => ($dateFormat) ? date('d.m.y G:i:s', $review['created_at'])
                        : $review['created_at'],
                    'shop_name'          => $review['shop_name'],
                    'is_old'             => ((time() - $review['created_at']) > 604800) ? true : false
                ];

                /**
                 * @var  $shop ShopProfileEntity
                 * @var  $user UserProfileEntity
                 */
                $shop = ShopProfileEntity::findOne(['user_id' => $review['created_by']]);
                $user = UserProfileEntity::findOne(['user_id' => $review['created_by']]);
                $userModel = UserEntity::findOne(['id' => $review['created_by']]);

                if ($shop) {
                    $reviewWithoutUserName['creator'] = [
                        'id'        => $shop->user_id,
                        'name'      => $shop->name,
                        'avatar'    => $shop->image,
                        'is_online' => $review['status_online']
                    ];
                } else {
                    $reviewWithoutUserName['creator'] = [
                        'id'        => $user->user_id,
                        'name'      => $user->nickname,
                        'avatar'    => $user->avatar,
                        'is_online' => $review['status_online']
                    ];
                }

                if ($userModel->status == UserEntity::STATUS_VERIFIED && $userModel->is_deleted == 0) {
                    $reviews[] = $reviewWithoutUserName;
                }
            }
        }

        if ($asDataProvider) {
            $dataProvider = new ArrayDataProvider([
                'allModels'  => $reviews,
                'pagination' => [
                    'pageSize' => Yii::$app->params['reviewsPerPage']
                ]
            ]);

            return $dataProvider;
        }

        if ($forLoad) {
            return array_slice($reviews, $start, Yii::$app->params['reviewsPerPage']);
        }

        return $reviews;
    }

    /**
     * Method of add shop review
     *
     * @param array $postData Data from POST request
     * @param bool $forAjax if result using for Ajax request
     * @return array | bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function addShopReview($postData, $forAjax = false)
    {
        $this->setScenario(self::SCENARIO_CREATE);

        if (!isset($postData['created_by'])) {
            $postData['created_by'] = Yii::$app->user->identity->getId();
        }

        $this->setAttributes($postData);

        try {
            $this->validate();
            $this->average_rating = $this->calculateAverageRating($postData);
            if ($this->save()) {
                Yii::$app->getResponse()->setStatusCode(201, 'Created');
                if ($forAjax) {
                    return true;
                }
                return [
                    'status'  => Yii::$app->response->statusCode,
                    'message' => 'Отзыв о магазине успешно добавлен',
                    'data'    => $this->getAttributes()
                ];
            }
            $this->validationExceptionFirstMessage($this->errors);
            $this->validateReviewer();

        } catch (ExceptionDb $e) {
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при добавлении отзыва о магазине.');
        }

        throw new ServerErrorHttpException('Произошла ошибка при добавлении отзыва о магазине.');

    }
}