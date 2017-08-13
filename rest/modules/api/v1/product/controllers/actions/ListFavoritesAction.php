<?php

namespace rest\modules\api\v1\product\controllers\actions;

use common\{
    models\product\ProductEntity, behaviors\AccessUserBehavior
};
use yii\rest\Action;

/**
 * Class GetProfileAction Action
 *
 * @mixin AccessUserBehavior
 *
 * @package rest\modules\api\v1\product\controllers\actions
 */
class ListFavoritesAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'   => AccessUserBehavior::className(),
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * List favorite products action
     *
     * @return array|\yii\data\ArrayDataProvider|\yii\db\ActiveRecord[]
     */
    public function run()
    {
        /** @var  $productModel ProductEntity */
        $productModel = new $this->modelClass();

        return $productModel->getFavoritesProductsByUser();
    }
}
