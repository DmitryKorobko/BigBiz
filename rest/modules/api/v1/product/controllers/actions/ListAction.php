<?php

namespace rest\modules\api\v1\product\controllers\actions;

use common\behaviors\{ValidateGetParameters, AccessUserBehavior};
use common\models\product\ProductEntity;
use yii\rest\Action;
use Yii;

/**
 * Class ListAction
 *
 * @mixin ValidateGetParameters
 * @mixin AccessUserBehavior
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class ListAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['shop_id']
            ],
            [
                'class'   => AccessUserBehavior::className(),
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
     * Action of getting list of product
     *
     * @return mixed
     */
    public function run()
    {
        /** @var  $productModel ProductEntity */
        $productModel = new $this->modelClass;

        return $productModel->getProducts(Yii::$app->request->queryParams['shop_id']);
    }
}