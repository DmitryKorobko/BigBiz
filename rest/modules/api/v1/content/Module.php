<?php

namespace rest\modules\api\v1\content;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\content
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\content\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
