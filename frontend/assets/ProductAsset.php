<?php

namespace frontend\assets;

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
        'css/cloud-zoom.css'
    ];
    public $js = [

        'libs/starrr/starrr.js',
        'js/product-star.js',
        'https://code.jquery.com/jquery-migrate-1.4.1.js',
        'js/cloud-zoom.1.0.2.js',
        'js/productFeedbackCreator.js',
        'js/productFeedbacksLoader.js'

    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
