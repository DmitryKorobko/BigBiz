<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\{
    theme\ThemeEntity, product\ProductEntity, mobile_banner\MobileBannerEntity, website_banner\WebsiteBannerEntity
};

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\manage\settings\controllers\SettingsController; */

$this->title = "Общие настройки";

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'key'
    ],
    [
        'attribute' => 'value',
        'value'     => function ($model) {
            if (
                $model['key']    === MobileBannerEntity::IMAGE_MAX
                || $model['key'] === MobileBannerEntity::IMAGE_MIN
                || $model['key'] === ThemeEntity::IMAGE_MAX
                || $model['key'] === ThemeEntity::IMAGE_MIN
                || $model['key'] === ProductEntity::IMAGE_MAX
                || $model['key'] === ProductEntity::IMAGE_MIN
                || $model['key'] === WebsiteBannerEntity::IMAGE_MAX
                || $model['key'] === WebsiteBannerEntity::IMAGE_MIN
            ) {
                return $model['value'] . ' КиБ';
            } elseif ($model['key'] === WebsiteBannerEntity::PRICE || $model['key'] === MobileBannerEntity::PRICE) {
                return $model['value'] . ' Грн';
            } else {
                return $model['value'];
            }
        },
        'filter' => false
    ],
    [
        'class'         => 'yii\grid\ActionColumn',
        'header'        => 'Действия',
        'template'      => '{update}&nbsp;&nbsp;&nbsp;{delete}',
        'headerOptions' => [
            'width' => '100px',
        ]
    ]
];
?>

<div>
    <div class="form-group">
        <?= Html::a('Добавить пункт настроек', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>