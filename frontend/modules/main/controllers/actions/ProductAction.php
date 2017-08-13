<?php
namespace frontend\modules\main\controllers\actions;

use yii\base\Action;
use common\models\{
    product_feedback\ProductFeedbackEntity, product\ProductEntity
};
use Yii;

/**
 * Class ProductAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class ProductAction extends Action
{
    public $view = '@frontend/modules/main/views/product';

    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'product';
    }

    /**
     * @param integer $id
     *
     * @return string
     */
    public function run($id):string
    {
        /** @var  $product ProductEntity*/
        $product = ProductEntity::findOne(['id' => $id]);
        /** @var  $productFeedback ProductFeedbackEntity*/
        $productFeedback = new ProductFeedbackEntity();
        $feedbacksDataProvider = $productFeedback->getListProductFeedbacks($product->id, true);
        $productFeedbacks = $feedbacksDataProvider->models;
        $prices = $product->getListProductPrices($product->id);
        $userId = ((!empty(Yii::$app->user->id)) ? Yii::$app->user->identity->getId() : 'quest');

        return $this->controller->render($this->view, [
            'product'               => $product,
            'productFeedbacks'      => $productFeedbacks,
            'feedbacksDataCount'    => $feedbacksDataProvider->count,
            'feedbacksAllDataCount' => $feedbacksDataProvider->totalCount,
            'userId'                => $userId,
            'prices'                => $prices,
            'productInFavorites'    => (($userId != 'quest') ? $product->isFavoriteProductByUser($product->id, $userId)
                : false),
            'cities'                => $product->getListProductCities($product->id),
            'productStarRating'     => $productFeedback->getReviewsProduct($product->id, true),
            'feedbacksCount'        => count($feedbacksDataProvider->allModels)
        ]);
    }
}