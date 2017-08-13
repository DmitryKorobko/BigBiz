<?php

namespace rest\modules\api\v1\shop;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\shop
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\shop\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
