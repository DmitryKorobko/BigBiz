<?php

namespace rest\modules\api\v1\support;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\support
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\support\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}