<?php

use yii\{
    grid\GridView, helpers\Html, widgets\Pjax, helpers\Url, helpers\ArrayHelper
};
use common\models\{
    child_category_section\ChildCategorySectionEntity, comment\CommentEntity, theme\ThemeEntity
};
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\shop\control\controllers\ThemeController */
/* @var $searchModel ThemeEntity */

$this->title = "Список тем";

$this->params['breadcrumbs'][] = $this->title;

Pjax::begin();
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'name',
        'filter'    => true
    ],
    [
        'attribute' => 'image',
        'header'  => 'Изображение',
        'format'  => 'raw',
        'content' => function ($model) {
            $imagePath = Yii::getAlias('@webroot') . str_replace('/admin', '', $model->image);
            if ($model->image && file_exists($imagePath)) {
                return Html::img($model->image, [ 'style' => 'width:200px;' ]);
            } else {
                return Html::img(Url::toRoute('/images/default/no_image.png'), [ 'style' => 'width:200px;' ]);
            }
        },
        'headerOptions' => [
            'width' => '300px',
        ]
    ],
    [
        'label'     => 'Категория темы',
        'attribute' => 'category_id',
        'format'    => 'html',
        'filter'    => Html::activeDropDownList(
            $searchModel,
            'category_id',
            ArrayHelper::map(ChildCategorySectionEntity::find()->where(['permissions_only_admin' => 0])
                ->asArray()->all(), 'id', 'name'),
            ['class' => 'form-control', 'value' => Yii::$app->request->get('ThemeEntity')['category_id'], 'prompt' => '']
        ),
        'value'     => function ($model) {
            $category = ChildCategorySectionEntity::findOne(['id' => $model->category_id]);
            return ($category) ? $category->name : 'Собственная тема';
        },
        'headerOptions' => [
            'width' => '350px',
        ]
    ],
    [
        'attribute' => 'comments_count',
        'filter'    => false,
        'headerOptions' => [
            'width' => '50px',
        ]
    ],
    [
        'attribute' => 'new_comments_count',
        'filter'    => false,
        'value'     => function($model) {
            $count = CommentEntity::find()->where(['theme_id' => $model->id, 'status' => CommentEntity::STATUS_UNREAD,
                'created_by' => Yii::$app->user->identity->id])->count();
            return $count;
        },
        'headerOptions' => [
            'width' => '50px',
        ]
    ],
    [
        'attribute' => 'view_count',
        'filter'    => false,
        'headerOptions' => [
            'width' => '50px',
        ]
    ],
    [
        'attribute' => 'status',
        'filter'    => [
            ThemeEntity::STATUS_VERIFIED   => 'Опубликована',
            ThemeEntity::STATUS_UNVERIFIED => 'На проверке'
        ],
        'value' => function ($model) {
            return ($model->status === ThemeEntity::STATUS_VERIFIED) ? 'Опубликована' : 'На проверке';
        },
        'headerOptions' => [
            'width' => '50px',
        ]
    ],
    [
        'attribute' => 'created_at',
        'value'     => function ($model) {
            return Yii::$app->formatter->asDatetime($model->created_at);
        },
        'filter'    => DateRangePicker::widget([
            'model'          => $searchModel,
            'attribute'      => 'theme_created_range',
            'convertFormat'  => true,
            'pluginOptions'  => [
                'timePicker' => true,
                'timePickerIncrement' => 30,
                'locale' => [
                    'format' => 'Y-MM-d H:i:s',
                ]
            ]
        ])
    ],
    [
        'class'         => 'yii\grid\ActionColumn',
        'template'      => '{view}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
        'buttons'       => [
            'view' => function ($url, $model) {
                return Html::a(('<span class="glyphicon glyphicon-eye-open"></span>'),
                    '/../main/shop-profile/theme?id='. $model->id,
                    [
                        'title'     => 'Предпросмотр темы',
                        'target'    => '_blank',
                        'data-pjax' => '0'
                    ]);
            }
        ],
        'headerOptions' => [
            'width' => '100px',
        ]
    ]
];
?>

<div>
    <div class="form-group">
        <?= Html::a('Добавить тему', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Очистить фильтры', ['index'], ['class' => 'btn btn-primary pull-right']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,

    ]); ?>
    <?php Pjax::end(); ?>


</div>