<?php

namespace rest\modules\api\v1\authorization;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\authorization
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\authorization\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
