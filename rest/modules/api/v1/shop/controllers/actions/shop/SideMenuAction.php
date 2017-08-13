<?php

namespace rest\modules\api\v1\shop\controllers\actions\shop;

use common\{
    models\shop_profile\ShopProfileEntity, behaviors\AccessShopBehavior
};
use Yii;
use yii\rest\Action;

/**
 * Class SideMenuAction
 *
 * @mixin AccessShopBehavior
 *
 * @package rest\modules\api\v1\shop\controllers\actions\shop
 */
class SideMenuAction extends Action
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'   => AccessShopBehavior::className(),
                'message' => 'Данная страница доступна только для магазинов.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun(): bool
    {
        $this->checkUserRole();

        return parent::beforeRun();
    }

    /**
     * Action of getting side menu of shop.
     *
     * @return array
     */
    public function run(): array
    {
        /** @var  $review ShopProfileEntity.php */
        $review = new $this->modelClass;

        return $review->getShopSideMenu(Yii::$app->user->identity->getId());
    }
}