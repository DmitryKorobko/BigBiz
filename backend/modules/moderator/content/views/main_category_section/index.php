<?php

use yii\{
    grid\GridView, helpers\Html, widgets\Pjax
};

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\moderator\content\controllers\MainCategorySectionController; */

$this->title = "Категории разделов первого уровня";

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'name'
    ],
    [
        'attribute' => 'sort',
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
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>