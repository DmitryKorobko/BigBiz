<?php
namespace common\behaviors;

use Yii;
use yii\{
    base\Behavior, web\HttpException
};

/**
 * Class AccessModeratorBehavior
 *
 * @package common\behaviors
 */
class AccessModeratorBehavior extends Behavior
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
        if (!(Yii::$app->user->can('moder'))) {
            throw new HttpException(403, $this->message);
        }
    }
}
