<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Product asset bundle.
 */
class ProductAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $sourcePath = '@bower/';
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css',
        'css/product.css',
        'css/custom-color.css',
        'css/cloud-zoom.css'
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js',
        'https://code.jquery.com/jquery-migrate-1.4.1.js',
        'js/cloud-zoom.1.0.2.js'

    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
