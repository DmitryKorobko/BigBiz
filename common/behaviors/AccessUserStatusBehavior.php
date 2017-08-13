<?php
namespace common\behaviors;

use Yii;
use yii\{
    base\Behavior, web\HttpException
};
use rest\models\RestUser;


/**
 * Class AccessUserStatusBehavior
 *
 * @package common\behaviors
 */
class AccessUserStatusBehavior extends Behavior
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
        $user = RestUser::findOne(['id' => Yii::$app->user->identity->getId()]);
        if (!$user || in_array($user->status, [RestUser::STATUS_GUEST, RestUser::STATUS_UNVERIFIED,
            RestUser::STATUS_BANNED, RestUser::STATUS_DELETED])) {
            throw new HttpException(403, $this->message);
        }
    }
}
