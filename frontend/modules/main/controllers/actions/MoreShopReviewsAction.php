<?php
namespace frontend\modules\main\controllers\actions;

use common\models\shop_feedback\ShopFeedbackEntity;
use yii\{
    base\Action, helpers\BaseJson
};
use Yii;
use stdClass;

/**
 * Class MoreShopReviewsAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class MoreShopReviewsAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'more-shop-reviews';
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var  $review ShopFeedbackEntity*/
        $review = new ShopFeedbackEntity();
        $shop  = Yii::$app->request->post()['shop'];
        $limit = Yii::$app->request->post()['limit'];

        $result = new stdClass();
        $result->limit = $limit + Yii::$app->params['reviewsPerPage'];
        $result->reviews = $review->getListShopReviews(null, $shop, false, true, $limit,
            true);

        return BaseJson::encode($result);
    }
}