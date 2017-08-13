<?php

namespace rest\modules\api\v1\shop\controllers\actions\shop;

use common\models\shop_profile\ShopProfileEntity;
use Yii;
use yii\rest\Action;

/**
 * Class ProfileAction
 *
 * @package rest\modules\api\v1\shop\controllers\actions\shop
 */
class ProfileAction extends Action
{

    /**
     * Action of getting own shop profile information
     *
     * @return array
     */
    public function run()
    {
        /** @var  $profile ShopProfileEntity.php */
        $profile = new $this->modelClass;

        return $profile->findProfile(Yii::$app->user->identity->getId());
    }
}
