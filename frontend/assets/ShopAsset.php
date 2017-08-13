<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Shop asset bundle.
 */
class ShopAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
  ];
    public $js = [
        'libs/starrr/starrr.js',
        'js/shop-profile.js',
        'js/shopThemesLoader.js',
        'js/shopReviewsLoader.js',
        'js/shopProductsLoader.js',
        'js/shopReviewCreator.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
