<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Category asset bundle.
 */
class CategoryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $sourcePath = '@bower/';
    public $css = [
    ];
    public $js = [
        'js/categoryThemesLoader.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
