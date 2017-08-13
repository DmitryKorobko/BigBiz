<?php
namespace common\behaviors;

use Yii;
use yii\{
    base\Behavior, web\HttpException
};
use common\models\shop_profile\ShopProfileEntity;


/**
 * Class AccessShopBehavior
 *
 * @package common\behaviors
 */
class AccessShopBehavior extends Behavior
{
    /**
     * @var array
     */
    public $message;

    /**
     * @throws HttpException
     */
    public function checkUserRole()
    {
        $shop = ShopProfileEntity::findOne(['user_id' => Yii::$app->user->identity->getId()]);
        if (!$shop) {
            throw new HttpException(403, $this->message);
        }
    }
}
