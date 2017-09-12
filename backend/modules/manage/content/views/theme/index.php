<?php

use yii\{
    grid\GridView, helpers\Html, widgets\Pjax, helpers\Url, helpers\ArrayHelper
};
use common\models\{
    child_category_section\ChildCategorySectionEntity, theme\ThemeEntity
};

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\shop\control\controllers\ThemeController */

$this->title = "Список тем";

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'user_name',
        'filter'    => true,
        'value'     => function ($model) { return $model->getThemeCreatorName($model->user_id); },
    ],
    [
        'attribute' => 'name',
        'filter'    => true
    ],
    [
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
            ArrayHelper::map(ChildCategorySectionEntity::find()->all(), 'id', 'name'),
            ['class' => 'form-control', 'value' => Yii::$app->request->get('ThemeEntity')['category_id'], 'prompt' => '']
        ),
        'value'     => function ($model) {
            $category = ChildCategorySectionEntity::findOne(['id' => $model->category_id ]);
            if ($category) {
                return $category->name;
            }
        },
        'headerOptions' => [
            'width' => '350px',
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
        'attribute' => 'comments_count',
        'filter'    => false,
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
        'class'         => 'yii\grid\ActionColumn',
        'template'      => '{view}&nbsp;&nbsp;{change-theme-status}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
        'buttons'       => [
            'change-theme-status' => function ($url, $model) {
                return Html::a(($model->status === ThemeEntity::STATUS_VERIFIED)
                    ? ('<span class="glyphicon glyphicon-check"></span>')
                    : '<span class="glyphicon glyphicon-unchecked"></span>',
                    $url,
                    [
                        'title' => ($model->status !== ThemeEntity::STATUS_VERIFIED)
                            ? 'Опубликовать тему' : 'Отключить отображение темы'
                    ]);
            },
            'delete' => function($url) {
                return Html::a(('<span class="glyphicon glyphicon-trash"></span>'),
                    $url,
                    [
                        'title' => 'Удалить тему',
                        'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?'
                    ]
                );
            },
            'view' => function ($url) {
                return Html::a(('<span class="glyphicon glyphicon-eye-open"></span>'),
                    $url,
                    [
                        'title' => 'Предпросмотр темы'
                    ]);
            }
        ],
        'headerOptions' => [
            'width' => '100px',
        ],
    ],
];
?>

<div>
    <div class="form-group">
        <?= Html::a('Добавить тему', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,

    ]); ?>
    <?php Pjax::end(); ?>


</div>