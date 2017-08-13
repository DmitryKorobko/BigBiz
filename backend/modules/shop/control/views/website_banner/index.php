<?php

use yii\{
    grid\GridView, helpers\Html, helpers\Url, widgets\Pjax
};
use common\models\settings\SettingsEntity;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\shop\control\controllers\WebsiteBannerController */

$this->title = "Список баннеров сайта";

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'header'  => 'Изображение',
        'format'  => 'raw',
        'content' => function ($model) {
            $imagePath = Yii::getAlias('@webroot') . str_replace('/admin', '', $model->image);
            if ($model->image && file_exists($imagePath)) {
                return Html::img($model->image, ['style' => 'width:200px;']);
            } else {
                return Html::img(Url::toRoute('/images/default/no_image.png'), ['style' => 'width:200px;']);
            }
        }
    ],
    [
        'attribute' => 'start_date',
        'format'    => ['date', 'php:F j, Y']
    ],
    [
        'attribute' => 'end_date',
        'format'    => ['date', 'php:F j, Y']
    ],
    [
        'attribute' => 'price',
        'value' => function($model) {
            $bannerPrice = SettingsEntity::getValueByKey('website_banner_price');
            $countDays = (int) (($model->end_date - $model->start_date) / 86400);
            return $countDays * $bannerPrice->value;
        }
    ],
    [
        'attribute' => 'count_days',
        'value' => function($model) {
           return (int) (($model->end_date - $model->start_date) / 86400);
        }
    ],
    [
        'attribute' => 'status',
        'value' => function($model) {
            return ($model->status) ? 'Включен' : 'Выключен';
        }
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
        <?= Html::a('Добавить баннер', ['create'], ['class' => 'btn btn-success pull-left']) ?>
        <?= Html::a('Связаться с администрацией', ['/shop/home/dashboard'], ['class' => 'btn btn-primary pull-right']) ?>
    </div>
    </br></br></br>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>