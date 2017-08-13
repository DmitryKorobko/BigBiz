<?php

namespace rest\modules\api\v1\shop\controllers\actions\review;

use common\{
    behaviors\ValidationExceptionFirstMessage, models\shop_feedback\ShopFeedbackEntity,
    behaviors\ValidatePostParameters, behaviors\AccessUserStatusBehavior
};
use Yii;
use yii\{
    rest\Action, web\NotFoundHttpException, web\ServerErrorHttpException
};

/**
 * Class UpdateShopReview Action
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\shop\controllers\actions\review
 */
class UpdateShopReviewAction extends Action
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
                    'shop_id', 'product_rating', 'operator_rating',
                    'reliability_rating', 'marker_rating'
                ]
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
    public function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of updating review about shop
     *
     * @return array|ShopFeedbackEntity
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        /* @var $review ShopFeedbackEntity.php */
        $review = ShopFeedbackEntity::findOne([
            'created_by' => Yii::$app->user->identity->getId(),
            'shop_id' => \Yii::$app->request->post()['shop_id']
        ]);

        if (!$review) {
            throw new NotFoundHttpException('Отзыв не найден.');
        }

        $review->scenario = ShopFeedbackEntity::SCENARIO_UPDATE;
        $review->load(Yii::$app->getRequest()->getBodyParams(), '');
        $review->average_rating = $review->calculateAverageRating($review);
        if ($review->save()) {
            Yii::$app->response->setStatusCode(200, 'OK');
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => 'Отзыв о магазине успешно изменён',
                'data'    => $review->getReviewDetail($review['shop_id'], $review['created_by'])
            ];
        } elseif ($review->hasErrors()) {
            ValidationExceptionFirstMessage::throwModelException($review->errors);
        }

        throw new ServerErrorHttpException('Произошла ошибка. Повторите попытку или сообщите об ошибке 
            администарации приложения.');
    }
}