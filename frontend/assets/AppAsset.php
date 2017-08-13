<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $sourcePath = '@bower/';
    public $css = [
        'css/preloader.css',
        'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css'
  ];
    public $js = [
        'https://code.jquery.com/jquery-2.1.4.min.js',
        'libs/jquery/jquery-1.11.2.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js',
        'js/scripts.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
