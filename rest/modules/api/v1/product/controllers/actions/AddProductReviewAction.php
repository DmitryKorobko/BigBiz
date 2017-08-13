<?php

namespace rest\modules\api\v1\product\controllers\actions;

use yii\rest\Action;
use common\{behaviors\ValidatePostParameters, behaviors\AccessUserStatusBehavior,
    models\product_feedback\ProductFeedbackEntity
};
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class AddProductReviewAction
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class AddProductReviewAction extends Action
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
                    'product_id', 'rating', 'text',
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
     * Add product in review list action
     *
     * @throws NotFoundHttpException
     * @return array
     */
    public function run()
    {
        /** @var  $productFeedbackModel ProductFeedbackEntity.php */
        $productFeedbackModel = new $this->modelClass();
        $productFeedbackModel->setScenario('create');

        return $productFeedbackModel->addProductReview(Yii::$app->request->post());
    }

}