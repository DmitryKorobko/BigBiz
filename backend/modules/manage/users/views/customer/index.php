<?php

use yii\{
    helpers\Html, widgets\Pjax, grid\GridView
};
use backend\models\BackendUserEntity;
use common\models\user\UserEntity;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\manage\users\controllers\CustomerController */
/* @var $searchModel backend\modules\manage\users\models\ShopSearch*/

$this->title = 'Блогеры/Покупатели';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin();
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'email',
        'filter'    => true
    ],
    [
        'attribute' => 'status',
        'value'     => function($model) {
            return UserEntity::getStatusName($model['status']);
        },
        'filter'    => [
            UserEntity::STATUS_VERIFIED   => 'Верифицирован',
            UserEntity::STATUS_UNVERIFIED => 'Неверифицированый',
            UserEntity::STATUS_GUEST      => 'Гость',
            UserEntity::STATUS_BANNED     => 'Забанен'
        ]
    ],
    [
        'attribute' => 'status_online',
        'label'     => 'Онлайн',
        'filter'    => ['Нет', 'Да'],
        'value'     => function($model) {
            return UserEntity::isOnline($model['id']) ? 'Да' : 'Нет';
        },
        'format' => 'boolean'
    ],
    [
        'attribute' => 'created_at',
        'label'     => 'Дата регистрации',
        'format'    => ['date', 'Y-MM-d H:i:s'],
        'filter'    => DateRangePicker::widget([
            'model'          => $searchModel,
            'attribute'      => 'datetime_range',
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
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{view}&nbsp;&nbsp;&nbsp;{delete}&nbsp;&nbsp;&nbsp;{ban-user}',
        'buttons'       => [
            'ban-user' => function ($url, $model) {
                return Html::a(($model['status'] !== BackendUserEntity::STATUS_BANNED)
                    ? ('<i class="fa fa-toggle-on" aria-hidden="true"></i>')
                    : '<i class="fa fa-toggle-off" aria-hidden="true"></i>',
                    $url,
                    [
                        'title' => ($model['status'] !== BackendUserEntity::STATUS_BANNED)
                            ? 'Забанить пользователя' : 'Разбанить пользователя'
                    ]
                );
            }
        ],
        'headerOptions' => [
            'width' => '100px',
        ],
    ]
];

?>
<div class="personal-info-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
