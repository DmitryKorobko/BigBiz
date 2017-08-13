<?php

use yii\{
    helpers\Html, widgets\Pjax, grid\GridView
};
use kartik\daterange\DateRangePicker;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\manage\users\controllers\ShopController */
/* @var $searchModel backend\modules\manage\users\models\ShopSearch */

$this->title = 'Магазины';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin();
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'name',
    'skype',
    [
        'attribute' => 'email',
        'filter'    => true,
        'value'     => function($model) {
            return $model->userEmail;
        }
    ],
    [
        'label'     => 'Сроки окончания аккаунта',
        'attribute' => 'category_end',
        'filter'    => DateRangePicker::widget([
            'model'          => $searchModel,
            'attribute'      => 'category_date_range',
            'convertFormat'  => true,
            'pluginOptions'  => [
                'timePicker' => true,
                'timePickerIncrement' => 30,
                'locale' => [
                    'format'    => 'Y-m-d h:i',
                ]
            ]
        ]),
        'format'    => ['date', 'Y-MM-d H:i:s'],
        'value'     => function ($model) {
            return $model->category_end;
        },
    ],
    [
        'label'     => 'Дата регистрации',
        'attribute' => 'created_at',
        'format'    => ['date', 'Y-MM-d H:i:s'],
        'filter'    => DateRangePicker::widget([
            'model'          => $searchModel,
            'attribute'      => 'created_date_range',
            'convertFormat'  => true,
            'pluginOptions'  => [
                'timePicker' => true,
                'timePickerIncrement' => 30,
                'locale' => [
                    'format' => 'Y-m-d h:i',
                ]
            ]
        ])
    ],
    [
        'class'         => 'yii\grid\ActionColumn',
        'template'      => '{view}&nbsp;&nbsp;&nbsp;{ban-shop}',
        'buttons'       => [
            'ban-shop' => function ($url, $model) {
                return Html::a(($model->user->status !== \backend\models\BackendUserEntity::STATUS_BANNED)
                    ? ('<i class="fa fa-toggle-on" aria-hidden="true"></i>')
                    : '<i class="fa fa-toggle-off" aria-hidden="true"></i>',
                    $url,
                    [
                        'title' => ($model->user->status !== \backend\models\BackendUserEntity::STATUS_BANNED)
                            ? 'Забанить магазин' : 'Разбанить магазин'
                    ]);
            }
        ],
        'headerOptions' => [
            'width' => '100px',
        ],
    ],
];

?>
<div class="personal-info-index">

    <h1>
        <?= Html::encode($this->title) ?>
        <?= Html::a('Очистить фильтры', ['index'], ['class' => 'btn btn-success pull-right']) ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>

</div>
