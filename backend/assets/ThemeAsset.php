<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Theme asset bundle.
 */
class ThemeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/theme.css',
        'css/custom-color.css'

  ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
