<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'        => 'app-frontend',
    'basePath'  => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'  => 'ru',
    'modules'   => [
        'main' => [
            'class' => \frontend\modules\main\Module::class,
        ],
    ],
    'defaultRoute' => 'main/main/index',
    'components'   => [
        'request'       => [
            'baseUrl' => '',
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
                '<module:main>/<action:(\w|-)+>'                           => '<module>/main/<action>',
                '<module:\w+>/<controller:\w+>/<action:(\w|-)+>'           => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>'  => '<module>/<controller>/<action>',
            ],
        ],
        'user'          => [
            'identityClass'   => 'common\models\user\UserEntity',
            'enableAutoLogin' => false,
            'loginUrl'        => ['main/login'],
            'authTimeout'     => 3600 // auth expire
        ],
    ],
    'params'       => $params,
];