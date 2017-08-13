<?php

namespace rest\modules\api\v1\product\controllers\actions;

use common\{
    behaviors\ValidationExceptionFirstMessage, models\product_feedback\ProductFeedbackEntity,
    behaviors\ValidatePostParameters, behaviors\AccessUserStatusBehavior
};
use yii\rest\Action;
use Yii;
use yii\web\{NotFoundHttpException, ServerErrorHttpException};

/**
 * Class UpdateProductReviewAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class UpdateProductReviewAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => [
                    'product_id', 'rating', 'text', 'review_id'
                ],
            ],
            [
                'class'   => AccessUserStatusBehavior::className(),
                'message' => 'Доступ запрещен.'
            ]
        ];
    }

    /**
     * @return bool
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of updating review about product
     *
     * @return ProductFeedbackEntity
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /* @var $productFeedback ProductFeedbackEntity.php */
        $productFeedback = ProductFeedbackEntity::findOne(['id' => Yii::$app->request->post()['review_id']]);
        if (!$productFeedback) {
            throw new NotFoundHttpException('Отзыв не найден');
        }
        $productFeedback->load(Yii::$app->request->post(), '');
        if ($productFeedback->save()) {
            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Отзыв товара успешно изменён',
                'data'    => $productFeedback->getReviewDetail(Yii::$app->request->post()['review_id'])
            ];
        } elseif ($productFeedback->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($productFeedback->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке 
            администарации приложения.');
    }
}