<?php

namespace rest\modules\api;

use Yii;

/**
 * Class Module
 * @package rest\modules\api
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->user->enableSession = false;
    }
}
