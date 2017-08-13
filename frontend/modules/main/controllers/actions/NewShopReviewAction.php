<?php
namespace frontend\modules\main\controllers\actions;


use common\models\shop_feedback\ShopFeedbackEntity;
use yii\{
    base\Action, web\ServerErrorHttpException, web\HttpException
};
use Yii;

/**
 * Class NewShopReviewAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class NewShopReviewAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'new-shop-review';
    }

    /**
     * @return bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function run(): bool
    {
        $creatorId = Yii::$app->request->post()['created_by'];
        $recipientId = Yii::$app->request->post()['shop_id'];
        /** @var  $review ShopFeedbackEntity*/
        $review = ShopFeedbackEntity::findOne(['created_by' => $creatorId, 'shop_id' => $recipientId]);

        if (!$review){
            $review = new ShopFeedbackEntity();
            $review->addShopReview(Yii::$app->request->post());
        } else {
            $review->scenario = ShopFeedbackEntity::SCENARIO_UPDATE;
            $review->load(Yii::$app->request->post(), '');
            $review->average_rating = $review->calculateAverageRating($review);
            if ($review->save()) {
                return true;
            } elseif (!$review->hasErrors()) {
                throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке 
            администарации приложения.');
            }
        }

        return true;
    }
}