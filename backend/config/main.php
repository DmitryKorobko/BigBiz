<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id'           => 'app-backend',
    'basePath'     => dirname(__DIR__),
    'bootstrap'    => ['log', 'AlertCategory'],
    'language'     => 'ru',
    'modules'      => [
        'authorization' => [
            'class' => \backend\modules\authorization\Module::class,
        ],
        'manage'        => [
            'class' => \backend\modules\manage\Module::class,
            'modules' => [
                'content' => [
                    'class' => \backend\modules\manage\content\Module::class
                ],
                'home' => [
                    'class' => \backend\modules\manage\home\Module::class
                ],
                'settings' => [
                    'class' => \backend\modules\manage\settings\Module::class
                ],
                'profile' => [
                    'class' => \backend\modules\manage\profile\Module::class
                ],
                'users' => [
                    'class' => \backend\modules\manage\users\Module::class
                ]
            ]
        ],
        'shop'          => [
            'class' => \backend\modules\shop\Module::class,
            'modules' => [
                'control' => [
                    'class' => \backend\modules\shop\control\Module::class
                ],
                'home' => [
                    'class' => \backend\modules\shop\home\Module::class
                ],
                'profile' => [
                    'class' => \backend\modules\shop\profile\Module::class
                ],
                'support' => [
                    'class' => \backend\modules\shop\support\Module::class
                ]
            ]
        ],
        'moderator'        => [
            'class' => \backend\modules\moderator\Module::class,
            'modules' => [
                'content' => [
                    'class' => \backend\modules\moderator\content\Module::class
                ],
                'home' => [
                    'class' => \backend\modules\moderator\home\Module::class
                ]
            ]
        ],
    ],
    'defaultRoute' => 'authorization/authorization/login',
    'components'   => [
        'request'       => [
            'baseUrl' => '/admin',
        ],
        'log'           => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler'  => [
            'errorAction' => 'authorization/authorization/error',
        ],
        'assetManager'  => [
            'bundles' => [
                '@vendor\dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-black',
                ],
                'davidhirtz\yii2\timeago\TimeagoAsset' => [
                    // Load localized version based on Yii::$app->language. Default true.
                    'locale'   => true,
                    // Use short locale version if available. Default false.
                    'short'    => false,
                    // Plugin options, see plugin website for details. Default values below.
                    'settings' => [
                        'refreshMillis' => 60000,
                        'allowPast'     => true,
                        'allowFuture'   => false,
                        'localeTitle'   => false,
                        'cutoff'        => 0,
                        'autoDispose'   => true,
                        // Strings set here it will overwrite loaded locale config.
                        'strings'       => [
                            'prefixAgo'     => null,
                            'prefixFromNow' => null,
                            'suffixAgo'     => "ago",
                            'suffixFromNow' => "from now",
                            'inPast'        => 'any moment now',
                            'seconds'       => "less than a minute",
                            'minute'        => "about a minute",
                            'minutes'       => "%d minutes",
                            'hour'          => "about an hour",
                            'hours'         => "about %d hours",
                            'day'           => "a day",
                            'days'          => "%d days",
                            'month'         => "about a month",
                            'months'        => "%d months",
                            'year'          => "about a year",
                            'years'         => "%d years",
                            'wordSeparator' => " ",
                            'numbers'       => [],
                        ],
                    ],
                ],
            ],
        ],
        'urlManager'    => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => false,
            'rules'               => [
                '<module:authorization>/<action:(\w|-)+>'                  => '<module>/authorization/<action>',
                'logout'                                                   => '/authorization/authorization/logout',
                '<module:\w+>/<controller:\w+>/<action:(\w|-)+>'           => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>'  => '<module>/<controller>/<action>',
            ],
        ],
        'user'          => [
            'identityClass'   => 'common\models\user\UserEntity',
            'enableAutoLogin' => false,
            'loginUrl'        => ['authorization/login'],
            'authTimeout'     => 3600 // auth expire
        ],
        'AlertCategory' => [
            'class' => 'backend\components\AlertCategory',
        ],
    ],
    'params'       => $params,
];