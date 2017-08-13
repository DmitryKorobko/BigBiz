<?php
use yii\{
    widgets\Pjax, grid\GridView, helpers\Html, helpers\ArrayHelper
};
use common\models\{
    child_category_section\ChildCategorySectionEntity, theme\ThemeEntity
};

/* @var $dataProviderTheme \yii\data\ActiveDataProvider */
/* @var $modelTheme \common\models\theme\ThemeEntity */
?>

<div id="theme" class="tab-pane fade in active">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderTheme,
        'filterModel'  => $modelTheme,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'label'     => 'Категория темы',
                'attribute' => 'category_id',
                'format'    => 'html',
                'filter'    => Html::activeDropDownList(
                    $modelTheme,
                    'category_id',
                    ArrayHelper::map(ChildCategorySectionEntity::find()
                        ->asArray()->all(), 'id', 'name'),
                    ['class' => 'form-control', 'value' => Yii::$app->request
                        ->get('ThemeEntity')['category_id'], 'prompt' => '']
                ),
                'value'     => function ($model) {
                    $category = ChildCategorySectionEntity::findOne(['id' => $model->category_id ]);
                    return ($category) ? $category->name : 'Собственная тема модератора';
                }
            ],
            [
                'attribute' => 'comments_count',
                'filter' => false
            ],
            [
                'attribute' => 'view_count',
                'filter' => false
            ],
            [
                'attribute' => 'status',
                'filter'    => false,
                'value' => function($model) {
                    return ($model->status === ThemeEntity::STATUS_UNVERIFIED) ? 'На проверке' : 'Проверена';
                },
            ],
            [
                'class'         => 'yii\grid\ActionColumn',
                'header'        => 'Действия',
                'template'      => '{view-theme}&nbsp;&nbsp;&nbsp;{delete-theme}',
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
                    'delete-theme' => function($url, $model) {
                        return Html::a(('<span class="glyphicon glyphicon-trash"></span>'),
                            $url,
                            [
                                'title' => 'Удалить тему'
                            ]
                        );
                    },
                    'view-theme' => function ($url, $model) {
                        return Html::a(('<span class="glyphicon glyphicon-eye-open"></span>'),
                            $url,
                            [
                                'title' => 'Предпросмотр темы'
                            ]);
                    }
                ],
                'headerOptions' => [
                    'width' => '100px',
                ]
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>