<?php
return [
    'supportEmail'                  => 'admin@bigbiz.pp.ua',
    'adminEmail'                    => 'admin@bigbiz.pp.ua',
    'user.passwordResetTokenExpire' => 3600,
    'default_rest_api_limit'        => 5,
    's3_folders'                    => [
        'user_profile' => 'user_profile',
        'shop_profile' => 'shop_profile'
    ],
    // count of items per page
    'shopsPerPage'            => 8,
    'productsPerPage'         => 12,
    'themesPerPage'           => 6,
    'reviewsPerPage'          => 6,
    'productFeedbacksPerPage' => 5,
    // min-max size for upload images
    'theme_image_max_size'    => 1024 * 1024 * 5,
    'theme_image_min_size'    => 1024 * 100,
    'mobile_banner_max_size'  => 1024 * 1024 * 5,
    'mobile_banner_min_size'  => 1024 * 100,
    'product_image_max_size'  => 1024 * 1024 * 5,
    'product_image_min_size'  => 1024 * 100,
    'website_banner_max_size' => 1024 * 1024 * 5,
    'website_banner_min_size' => 1024 * 100,
];
