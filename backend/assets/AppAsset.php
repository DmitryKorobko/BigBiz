<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $sourcePath = '@bower/';
    public $css = [
        'css/common.css',
        'css/star-rating.min.css',
        'css/avatar-upload.css',
        'css/ionicons.min.css'
    ];
    public $js = [
        'js/star-rating.min.js',
        'js/status-online.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
