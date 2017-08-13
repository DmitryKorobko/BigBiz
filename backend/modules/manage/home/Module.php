<?php
namespace backend\modules\manage\home;

/**
 * Class Module
 *
 * @package backend\modules\manage\home
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\manage\home\controllers';

    public $defaultRoute = 'manage/home/index';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}