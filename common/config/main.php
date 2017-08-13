<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'       => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'mailer'      => [
            'class'    => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/views/mail'
        ],
        's3'          => [
            'class'         => \frostealth\yii2\aws\s3\Service::class,
            'credentials'   => [
                'key'    => 'AKIAI5EJMPRPES6OW2NA',
                'secret' => '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',
            ],
            'region'        => 'us-west-2',
            'defaultBucket' => 'bigbiz',
            'defaultAcl'    => 'public-read'
        ],
        'formatter' => [
            'class'    => 'yii\i18n\Formatter',
            'timeZone' => 'Europe/Moscow',
            'locale'   => 'ru-RU'
        ],
    ],
];
