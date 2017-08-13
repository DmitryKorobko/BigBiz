<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
);

$routeRules = require(__DIR__ . '/routes.php');

return [
    'id'         => 'app-rest',
    'language'   => 'ru',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'modules'    => [
        'api' => [
            'class'   => 'rest\modules\api\Module',
            'modules' => [
                'v1' => [
                    'class'   => 'rest\modules\api\v1\Module',
                    'modules' => [
                        'authorization' => [
                            'class' => 'rest\modules\api\v1\authorization\Module'
                        ],
                        'user' => [
                            'class' => 'rest\modules\api\v1\user\Module'
                        ],
                        'shop' => [
                            'class' => 'rest\modules\api\v1\shop\Module'
                        ],
                        'content' => [
                            'class' => 'rest\modules\api\v1\content\Module'
                        ],
                        'message' => [
                            'class' => 'rest\modules\api\v1\message\Module'
                        ],
                        'theme' => [
                            'class' => 'rest\modules\api\v1\theme\Module'
                        ],
                        'comment' => [
                            'class' => 'rest\modules\api\v1\comment\Module'
                        ],
                        'product' => [
                            'class' => 'rest\modules\api\v1\product\Module'
                        ],
                        'support' => [
                            'class' => 'rest\modules\api\v1\support\Module'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'components' => [
        'response'   => [
            'format'  => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8'
        ],
        'request'    => [
            'baseUrl'                => '/',
            'class'                  => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers'                => ['application/json' => 'yii\web\JsonParser'],
        ],
        'user'       => [
            'identityClass'   => 'rest\models\RestUser',
            'enableSession'   => false,
            'enableAutoLogin' => false
        ],
        'log'        => [
            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'          => 'yii\log\FileTarget',
                    'levels'         => ['info', 'error', 'warning', 'trace'],
                    'categories'     => 'shop',
                    'logVars'        => [],
                    'logFile'        => '@app/runtime/logs/shop.log',
                    'exportInterval' => 1,
                    'maxFileSize'    => 1024 * 2,
                    'maxLogFiles'    => 20
                ],
                [
                    'class'          => 'yii\log\FileTarget',
                    'levels'         => ['info', 'error', 'warning', 'trace'],
                    'categories'     => 'theme',
                    'logVars'        => [],
                    'logFile'        => '@app/runtime/logs/theme.log',
                    'exportInterval' => 1,
                    'maxFileSize'    => 1024 * 2,
                    'maxLogFiles'    => 20
                ]
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'baseUrl'             => '/',
            'rules'               => $routeRules
        ]
    ],
    'params'     => $params,
];