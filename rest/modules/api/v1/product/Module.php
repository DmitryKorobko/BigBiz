<?php

namespace rest\modules\api\v1\product;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\product
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\product\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
