<?php

namespace rest\modules\api\v1\message;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\message
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\message\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
