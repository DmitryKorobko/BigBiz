<?php

namespace rest\modules\api\v1\product\controllers\actions;

use common\{
    behaviors\AccessUserBehavior, models\product\ProductEntity, behaviors\ValidateGetParameters
};
use Yii;
use yii\rest\Action;

/**
 * Class Detail Action
 *
 * @mixin ValidateGetParameters
 * @mixin AccessUserBehavior
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class DetailAction extends Action
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
                'inputParams' => ['id']
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
    public function beforeRun(): bool
    {
        $this->validationParams();
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of getting product details
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function run()
    {
        /** @var  $product ProductEntity.php */
        $product = new $this->modelClass;

        return $product->getProductDetails(Yii::$app->request->queryParams);
    }
}
