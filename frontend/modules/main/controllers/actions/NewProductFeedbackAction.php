<?php
namespace frontend\modules\main\controllers\actions;


use common\models\product_feedback\ProductFeedbackEntity;
use yii\{
    base\Action, web\ServerErrorHttpException, web\HttpException
};
use Yii;

/**
 * Class NewProductFeedbackAction
 *
 * @package frontend\modules\main\controllers\actions
 */
class NewProductFeedbackAction extends Action
{
    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'new-product-feedback';
    }

    /**
     * @return bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function run(): bool
    {
        $creatorId = Yii::$app->request->post()['user_id'];
        $productId = Yii::$app->request->post()['product_id'];
        /** @var  $review ProductFeedbackEntity*/
        $feedback = ProductFeedbackEntity::findOne(['user_id' => $creatorId, 'product_id' => $productId]);

        if (!$feedback){
            $feedback = new ProductFeedbackEntity();
            $feedback->addNewProductFeedback(Yii::$app->request->post());
        } else {
            $feedback->scenario = ProductFeedbackEntity::SCENARIO_UPDATE;
            $feedback->load(Yii::$app->request->post(), '');
            if ($feedback->save()) {
                return true;
            } elseif (!$feedback->hasErrors()) {
                throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке 
            администарации приложения.');
            }
        }

        return true;
    }
}