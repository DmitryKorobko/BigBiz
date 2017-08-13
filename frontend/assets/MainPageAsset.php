<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MainPageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        'libs/fancybox/jquery.fancybox.css',
        'css/fonts.css',
        'css/font-awesome.min.css',
        'libs/colorbox/colorbox.css',
        'css/owl.carousel.min.css',
        'css/main.css',
        'css/custom-color.css'

  ];
    public $js = [
        'libs/fancybox/jquery.fancybox.js',
        'js/masonry.pkgd.min.js',
        'libs/colorbox/jquery.colorbox-min.js',
        'libs/colorbox/i18n/jquery.colorbox-ru.js',
        'js/jquery.stellar.min.js',
        'js/jquery.formstyler.min.js',
        'js/jquery.countto.js',
        'js/owl.carousel.min.js',
        'js/common.js',
        'js/main-page-scripts.js',
        'js/shopsLoad.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
