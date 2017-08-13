<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Authorization asset bundle.
 */
class AuthorizationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/authorization/font-awesome.min.css',
        'css/authorization/fonts.css',
        'css/authorization/materialize.min.css',
        'css/authorization/log-style.css'
    ];
    public $js = [
        'js/authorization/materialize.min.js',
        'js/authorization/scripts.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
