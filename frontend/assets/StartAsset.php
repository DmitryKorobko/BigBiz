<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class StartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $sourcePath = '@bower/';
    public $css = [
        'css/fonts.css',
        'css/font-awesome.min.css',
        'css/custom-color.css',
        'css/log-style.css'

    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
