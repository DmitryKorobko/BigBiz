<?php
namespace common\behaviors;

use Yii;
use yii\{
    base\Behavior, web\HttpException
};
use common\models\user_profile\UserProfileEntity;


/**
 * Class AccessUserBehavior
 *
 * @package common\behaviors
 */
class AccessUserBehavior extends Behavior
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
        $user = UserProfileEntity::findOne(['user_id' => Yii::$app->user->identity->getId()]);
        if (!$user) {
            throw new HttpException(403, $this->message);
        }
    }
}