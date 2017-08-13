<?php
namespace frontend\modules\main\controllers\actions;

use common\models\product_feedback\ProductFeedbackEntity;
use yii\{
    base\Action, helpers\BaseJson
};
use Yii;
use stdClass;

/**
 * Class MoreProductFeedbacksAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class MoreProductFeedbacksAction extends Action
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
        return 'more-product-feedbacks';
    }

    /**
     * @return string
     */
    public function run(): string
    {
        /** @var  $feedback ProductFeedbackEntity*/
        $feedback = new ProductFeedbackEntity();
        $productId  = Yii::$app->request->post()['product_id'];
        $limit = Yii::$app->request->post()['limit'];

        $result = new stdClass();
        $result->limit = $limit + Yii::$app->params['productFeedbacksPerPage'];
        $result->reviews = $feedback->getListProductFeedbacks($productId, false, true,  $limit,
            true);

        return BaseJson::encode($result);
    }
}