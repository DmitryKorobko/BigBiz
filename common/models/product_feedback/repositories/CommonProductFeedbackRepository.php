<?php
namespace common\models\product_feedback\repositories;

use common\models\{
    product_feedback\ProductFeedbackEntity, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity,
    product_like\ProductLikeEntity, user\UserEntity
};
use yii\{
    base\ErrorHandler, base\Exception, web\HttpException, web\ServerErrorHttpException,
    db\Exception as ExceptionDb, data\ArrayDataProvider
};
use Yii;

/**
 * Class CommonProductFeedbackRepository
 *
 * @package common\models\product_feedback\repositories
 */
trait CommonProductFeedbackRepository
{
    /**
     * Method of getting list product feedbacks
     *
     * @param integer $productId
     * @param bool $asDataProvider If result must be as ArrayDataProvider
     * @param bool $forLoad If result using in load more function
     * @param integer $start Number of first element of result array in main array
     * @param bool $dateFormat If date in result must be ('d.m.y') format
     * @return ArrayDataProvider | array
     */
    public function getListProductFeedbacks($productId, $asDataProvider = false, $forLoad = false,
                                            $start = null, $dateFormat = false)
    {
        $verifiedUserIds = UserEntity::find()->select('id')->where(['status' => UserEntity::STATUS_VERIFIED]);
        $productFeedbacks = [];
        $models = ProductFeedbackEntity::find()
            ->select(['product_feedback.id', 'text', 'user_id', 'rating', 'product_feedback.created_at', 'product_id',
                'user.status_online as status_online'])
            ->leftJoin('user', 'user.id = product_feedback.user_id')
            ->where(['product_id' => $productId, 'user_id' => $verifiedUserIds])
            ->orderBy(['product_feedback.created_at' => SORT_DESC])
            ->asArray()
            ->all();

        if ($models) {
            foreach ($models as $model) {

                /** Get information about author of comment */
                $authorShop = ShopProfileEntity::findOne(['user_id' => $model['user_id']]);
                $authorUser = UserProfileEntity::findOne(['user_id' => $model['user_id']]);

                if ($dateFormat) {
                    $model['created_at'] = date('d.m.y', $model['created_at']);
                }

                if ($authorShop) {
                    $author = [
                        'name'          => $authorShop->name,
                        'avatar'        => $authorShop->image,
                        'status_online' => $model['status_online']
                    ];
                } else if ($authorUser) {
                    $author = [
                        'name'          => $authorUser->nickname,
                        'avatar'        => $authorUser->avatar,
                        'status_online' => $model['status_online']
                    ];
                } else {
                    $author = [
                        'name'          => 'Администрация',
                        'avatar'        => null,
                        'status_online' => $model['status_online']
                    ];
                }

                $productFeedbacks[] = [
                    'author'            => $author,
                    'productFeedback'   => $model
                ];
            }
        }

        if ($asDataProvider) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $productFeedbacks,
                'pagination' => [
                    'pageSize' => Yii::$app->params['productFeedbacksPerPage']
                ]
            ]);

            return $dataProvider;
        }

        if ($forLoad) {
            return array_slice($productFeedbacks, $start, Yii::$app->params['productFeedbacksPerPage']);
        }

        return $productFeedbacks;
    }

    /**
     *  Method of getting product reviews by productId
     *
     * @param $productId
     * @param $asDetailRating
     * @return ArrayDataProvider | array
     */
    public function getReviewsProduct($productId, $asDetailRating = false)
    {
        /** @var $reviews ProductFeedbackEntity */
        $reviews = ProductFeedbackEntity::find()
            ->where(['product_feedback.product_id' => $productId])
            ->select(['product_feedback.id', 'product_feedback.product_id', 'product_feedback.user_id',
                'product_feedback.text', 'product_feedback.rating', 'product_feedback.created_at',
                'user.status_online', 'user_profile.nickname', 'user_profile.avatar'
            ])
            ->leftJoin('user', 'product_feedback.user_id = user.id')
            ->leftJoin('user_profile', 'product_feedback.user_id = user_profile.user_id')
            ->orderBy('id DESC')
            ->asArray()
            ->all();

        if ($asDetailRating) {
            $rating = ['oneStar' => 0, 'twoStars' => 0, 'threeStars' => 0, 'fourStars' => 0, 'fiveStars' => 0];
            $allStarsCount = 0;

            foreach ($reviews as $review) {
                $allStarsCount++;
                ($review['rating'] == 1) ? $rating['oneStar']++ :
                    ($review['rating'] == 2) ? $rating['twoStars']++ :
                        ($review['rating'] == 3) ? $rating['threeStars']++ :
                            ($review['rating'] == 4) ? $rating['fourStars']++ :
                                ($review['rating'] == 5) ? $rating['fiveStars']++ : true;
            }

            $starsPercents = [
                'oneStar'    => ($allStarsCount != 0) ? round(($rating['oneStar'] / $allStarsCount) * 100) : 0,
                'twoStars'   => ($allStarsCount != 0) ? round(($rating['twoStars'] / $allStarsCount) * 100) : 0,
                'threeStars' => ($allStarsCount != 0) ? round(($rating['threeStars'] / $allStarsCount) * 100) : 0,
                'fourStars'  => ($allStarsCount != 0) ? round(($rating['fourStars'] / $allStarsCount) * 100) : 0,
                'fiveStars'  => ($allStarsCount != 0) ? round(($rating['fiveStars'] / $allStarsCount) * 100) : 0
            ];
            $rating['starsPercents'] = $starsPercents;

            return $rating;

        } else {
            $productLikesCount = (new ProductLikeEntity)->getCountProductLike(Yii::$app->request->queryParams['product_id']);

            $result = [];
            foreach ($reviews as $review) {
                $tmpDataReviews = [
                    'id'            => (int) $review['id'],
                    'product_id'    => (int) $review['product_id'],
                    'user_id'       => (int) $review['user_id'],
                    'text'          => $review['text'],
                    'rating'        => (int) $review['rating'],
                    'created_at'    => (int) $review['created_at'],
                    'status_online' => (int) $review['status_online'],
                    'nickname'      => $review['nickname'],
                    'avatar'        => $review['avatar'],
                    'likes_count'   => (int) $productLikesCount
                ];

                $result[] = $tmpDataReviews;
            }

            $dataProvider = new ArrayDataProvider([
                'allModels'  => $result,
                'pagination' => [
                    'pageSize' => 10
                ]
            ]);

            return $dataProvider;
        }
    }

    /**
     * Method of add product feedback
     *
     * @param array $postData Data from POST request
     * @param bool $forAjax if result using for Ajax request
     * @return array | bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function addNewProductFeedback($postData, $forAjax = false)
    {
        $this->setScenario(self::SCENARIO_CREATE);

        if (!isset($postData['user_id'])) {
            $postData['user_id'] = Yii::$app->user->identity->getId();
        }

        $this->setAttributes($postData);

        try {
            if ($this->save()) {
                Yii::$app->getResponse()->setStatusCode(201);
                if ($forAjax) {
                    return true;
                }
                return ['product_feedback_id' => (int) $this->id];
            }
            $this->isExistingProduct();

        } catch (ExceptionDb $e) {
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при добавлении отзыва о товаре.');
        }

        throw new ServerErrorHttpException('Произошла ошибка при добавлении отзыва о товаре.');

    }
}