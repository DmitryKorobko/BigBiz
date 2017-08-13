<?php

namespace rest\modules\api\v1\shop\controllers\actions\review;

use Yii;
use yii\rest\Action;
use common\{
    models\shop_feedback\ShopFeedbackEntity, behaviors\ValidateGetParameters, behaviors\AccessUserStatusBehavior
};

/**
 * Class ReviewDetail Action
 *
 * @mixin ValidateGetParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\shop\controllers\actions\review
 */
class ReviewDetailAction extends Action
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
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['shop_id']
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
     * Action of getting review details
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function run()
    {
        /* @var $review ShopFeedbackEntity.php */
        $review = new ShopFeedbackEntity();

        return $review->getReviewDetail(Yii::$app->request->queryParams['shop_id'], Yii::$app->user->identity->getId());
    }
}
