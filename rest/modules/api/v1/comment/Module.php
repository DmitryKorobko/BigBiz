<?php

namespace rest\modules\api\v1\comment;

/**
 * Class Module
 *
 * @package rest\modules\api\v1\comment
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rest\modules\api\v1\comment\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
