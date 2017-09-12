<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\shop\control\controllers\DeliveryController */

$this->title = "Список адресов доставки для товаров";

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    [
        'attribute' => 'name',
        'header'    => 'Название товара',
        'value'     => function ($model) {
            return $model->name;
        }
    ],
    [
        'attribute' => 'address',
        'value'     => function ($model) {
            return $model->address;
        }
    ],
    [
        'attribute' => 'created_at',
        'value'     => function ($model) {
            return Yii::$app->formatter->asDate($model->created_at);
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
        <?= Html::a('Добавить адрес', ['create'], ['class' => 'btn btn-success pull-left']) ?>
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