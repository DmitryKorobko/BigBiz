<?php
namespace backend\modules\moderator\home;

/**
 * Class Module
 *
 * @package backend\modules\moderator\home
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\moderator\home\controllers';

    public $defaultRoute = 'moderator/home/index';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}