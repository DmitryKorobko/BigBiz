<?php
return [
    /** Authorization module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'authorization'                         => 'api/v1/authorization/authorization',
            'authorization/password-recovery'       => 'api/v1/authorization/authorization',
            'authorization/register'                => 'api/v1/authorization/authorization',
            'authorization/send-security-code'      => 'api/v1/authorization/authorization',
            'authorization/generation-access-token' => 'api/v1/authorization/authorization'
        ],
        'patterns'   => [
            'POST login'                   => 'login',
            'GET logout'                   => 'logout',
            'POST login-guest'             => 'login-guest',
            'POST password-recovery'       => 'password-recovery',
            'POST register'                => 'register',
            'POST send-security-code'      => 'send-security-code',
            'POST generation-access-token' => 'generation-access-token'
        ]
    ],
    /** User module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'user'                       => 'api/v1/user/user',
            'user/common-list-favorites' => 'api/v1/user/user',
            'user/add-user-reputation'   => 'api/v1/user/user',
            'user/change-status-online'  => 'api/v1/user/user',
        ],
        'patterns'   => [
            'GET common-list-favorites' => 'common-list-favorites',
            'POST add-user-reputation'  => 'add-user-reputation',
            'GET change-status-online'  => 'change-status-online'
        ]
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'user/theme'        => 'api/v1/user/theme',
            'user/theme/create' => 'api/v1/user/theme',
            'user/theme/update' => 'api/v1/user/theme',
            'user/theme/delete' => 'api/v1/user/theme'
        ],
        'patterns'   => [
            'PUT {id}'    => 'update',
            'POST'        => 'create',
            'DELETE {id}' => 'delete'
        ]
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'user'                         => 'api/v1/user/profile',
            'user/profile'                 => 'api/v1/user/profile',
            'user/profile/update-password' => 'api/v1/user/profile',
            'user/profile/side-menu'       => 'api/v1/user/profile',
            'user/profile/answers'         => 'api/v1/user/profile',
            'user/profile/settings'        => 'api/v1/user/profile',
            'user/profile/avatar'          => 'api/v1/user/profile'
        ],
        'patterns'   => [
            'PUT  profile'                 => 'update-profile',
            'GET  profile'                 => 'get-profile',
            'POST profile/update-password' => 'update-password',
            'GET profile/side-menu'        => 'side-menu',
            'GET profile/answers'          => 'answers',
            'GET profile/settings'         => 'settings',
            'DELETE profile'               => 'delete-profile',
            'PUT  profile/avatar'          => 'update-avatar'
        ]
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'user'        => 'api/v1/user/device',
            'user/device' => 'api/v1/user/device',
        ],
        'patterns'   => [
            'POST' => 'create'
        ]
    ],

    /** Shop module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'pluralize'  => false,
        'controller' => [
            'shop'             => 'api/v1/shop/shop',
            'shop/list'        => 'api/v1/shop/shop',
            'shop/profile'     => 'api/v1/shop/shop',
            'shop/preview'     => 'api/v1/shop/shop',
            'shop/answers'     => 'api/v1/shop/shop',
            'shop/side-menu'   => 'api/v1/shop/shop',
            'shop/detail'      => 'api/v1/shop/shop'
        ],
        'patterns'   => [
            'GET list'          => 'index',
            'GET profile'       => 'profile',
            'GET preview'       => 'preview',
            'GET answers'       => 'answers',
            'GET side-menu'     => 'side-menu',
            'GET detail'        => 'detail'
        ]
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'shop'                    => 'api/v1/shop/review',
            'shop/reviews'            => 'api/v1/shop/shop',
            'shop/add-shop-review'    => 'api/v1/shop/shop',
            'shop/update-shop-review' => 'api/v1/shop/shop',
            'shop/review-detail'      => 'api/v1/shop/shop',
            'shop/rating'             => 'api/v1/shop/shop'
        ],
        'patterns'   => [
            'GET reviews'                 => 'reviews',
            'POST add-shop-review'        => 'add-shop-review',
            'PUT update-shop-review'      => 'update-shop-review',
            'GET review-detail'           => 'review-detail',
            'GET rating'                  => 'rating'
        ]
    ],

    /** Content module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'city' => 'api/v1/content/city'
        ],
        'patterns'   => ['GET' => 'index']
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'category'            => 'api/v1/content/category',
            'category/list-main'  => 'api/v1/content/category',
            'category/list-child' => 'api/v1/content/category',
        ],
        'patterns'   => [
            'GET list-main'  => 'list-main',
            'GET list-child' => 'list-child'
        ]
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'banner'      => 'api/v1/content/banner',
            'banner/list' => 'api/v1/content/banner',
        ],
        'patterns'   => [
            'GET list' => 'list'
        ]
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'image'                      => 'api/v1/content/image',
            'image/sign-s3-image-policy' => 'api/v1/content/image',
        ],
        'patterns'   => [
            'POST sign-s3-image-policy' => 'sign-s3-image-policy'
        ]
    ],

    /** Message module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'message'               => 'api/v1/message/message',
            'message/list-chats'    => 'api/v1/message/message',
            'message/delete-chat'   => 'api/v1/message/message',
            'message/history-chat'  => 'api/v1/message/message',
            'message/count-new'     => 'api/v1/message/message',
            'message/clearing-chat' => 'api/v1/message/message'
        ],
        'patterns'   => [
            'GET   count-new'    => 'count-new',
            'GET   list-chats'   => 'list-chats',
            'DELETE delete-chat' => 'delete-chat',
            'GET history-chat'   => 'history-chat',
            'POST'               => 'create',
            'PUT {id}'           => 'update',
            'DELETE {id}'        => 'delete',
            'POST cleaning-chat' => 'cleaning-chat'
        ]
    ],

    /** Comment module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'comment'      => 'api/v1/comment/comment',
            'comment/like' => 'api/v1/comment/comment',
            'comment/list' => 'api/v1/comment/comment'
        ],
        'except'       => ['view'],
        'patterns'   => [
            'GET'         => 'index',
            'POST'        => 'create',
            'DELETE {id}' => 'delete',
            'POST like'   => 'like',
            'PUT'         => 'update',
            'GET list'    => 'list'
        ]
    ],

    /** Theme module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'theme'                 => 'api/v1/theme/theme',
            'theme/list'            => 'api/v1/theme/theme',
            'theme/detail'          => 'api/v1/theme/theme',
            'theme/like-dislike'    => 'api/v1/theme/theme',
            'theme/shop-themes'     => 'api/v1/theme/theme'

        ],
        'patterns'   => [
            'GET list'                    => 'list',
            'GET detail'                  => 'detail',
            'POST like-dislike'           => 'like-dislike',
            'GET shop-themes'             => 'shop-themes',
            'POST   add-favorite'         => 'add-favorite',
            'DELETE delete-favorite/{id}' => 'delete-favorite',
            'GET    list-favorites'       => 'list-favorites'
        ]
    ],

    /** Product module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'product'                 => 'api/v1/product/product',
            'product/list'            => 'api/v1/product/product',
            'product/list-favorites'  => 'api/v1/product/product',
            'product/list-reviews'    => 'api/v1/product/product',
            'product/detail'          => 'api/v1/product/product',
            'product/add-favorite'    => 'api/v1/product/product',
            'product/delete-favorite' => 'api/v1/product/product',
            'product/like'            => 'api/v1/product/product',
            'product/add-review'      => 'api/v1/product/product',
            'product/update-review'   => 'api/v1/product/product',
        ],
        'patterns'   => [
            'GET list-favorites'          => 'list-favorites',
            'GET list'                    => 'list',
            'GET list-reviews'            => 'list-reviews',
            'GET detail'                  => 'detail',
            'POST add-favorite'           => 'add-favorite',
            'DELETE delete-favorite/{id}' => 'delete-favorite',
            'POST like'                   => 'like',
            'POST add-review'             => 'add-review',
            'PUT update-review'           => 'update-review',
        ],
    ],

    /** Support module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'support'             => 'api/v1/support/support',
            'support/send-letter' => 'api/v1/support/support'
        ],
        'patterns' => [
            'POST send-letter' => 'send-letter'
        ]
    ]
];
