<?php

namespace rest\modules\api\v1\product\controllers\actions;

use yii\rest\Action;
use common\{
    models\product_feedback\ProductFeedbackEntity, behaviors\ValidateGetParameters, behaviors\AccessUserBehavior
};
use Yii;

/**
 * Class ListReviewsAction
 *
 * @mixin ValidateGetParameters
 * @mixin AccessUserBehavior
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class ListReviewsAction extends Action
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
                'inputParams' => ['product_id']
            ],
            [
                'class'   => AccessUserBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @return bool
     */
    protected function beforeRun() : bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Method of getting list reviews product
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        /** @var  $productModel ProductFeedbackEntity.php */
        $productModel = new $this->modelClass;

        return $productModel->getReviewsProduct(Yii::$app->request->queryParams['product_id']);
    }
}