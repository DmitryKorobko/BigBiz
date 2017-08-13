<?php

namespace rest\modules\api\v1\theme;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\theme
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\theme\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}