<?php

namespace rest\modules\api\v1\shop\controllers\actions\review;

use yii\rest\Action;
use common\{
    behaviors\ValidatePostParameters, models\shop_feedback\ShopFeedbackEntity, behaviors\AccessUserStatusBehavior
};

/**
 * Class AddShopReviewAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\shop\controllers\actions\review
 */
class AddShopReviewAction extends Action
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
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of add shop review by userID
     * @return array
     */
    public function run(): array
    {
        /**
         * @var  $shopFeedbackModel ShopFeedbackEntity.php
         */
        $shopFeedbackModel = new $this->modelClass();

        return $shopFeedbackModel->addShopReview(\Yii::$app->request->post());
    }
}