<?php
namespace common\behaviors;

use Yii;
use yii\{
    base\Behavior, web\HttpException
};

/**
 * Class AccessAdminBehavior
 *
 * @package common\behaviors
 */
class AccessAdminBehavior extends Behavior
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
        if (!(Yii::$app->user->can('admin'))) {
            throw new HttpException(403, $this->message);
        }
    }
}
