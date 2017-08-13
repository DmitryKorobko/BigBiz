<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use \common\models\product_feedback\ProductFeedbackEntity;
use kartik\daterange\DateRangePicker;

/* @var $dataProviderProduct \yii\data\ActiveDataProvider */
/* @var $modelProduct \common\models\product\ProductEntity */
?>

<div id="product" class="tab-pane fade">
    <?php Pjax::begin(); ?>
    <div class="form-group">
        <?= Html::a('Очистить фильтры', ['view?id=' .
            Yii::$app->request->get()['id']], ['class' => 'btn btn-success']) ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProviderProduct,
        'filterModel'  => $modelProduct,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'filter'    => true
            ],
            [
                'header'        => 'Изображение',
                'format'        => 'raw',
                'content'       => function ($model) {
                    $imagePath = Yii::getAlias('@webroot') . str_replace('/admin', '', $model->image);
                    if ($model->image && file_exists($imagePath)) {
                        return Html::img($model->image, ['style' => 'width:200px;']);
                    } else {
                        return Html::img(\yii\helpers\Url::toRoute('/images/default/no_image.png'), ['style' => 'width:200px;']);
                    }
                },
                'headerOptions' => [
                    'width' => '300px',
                ]
            ],
            [
                'attribute' => 'prices',
                'format'    => 'html',
                'value'     => function ($model) {
                    $priceHtml = "<ul>";
                    foreach ($model->prices as $price) {
                        $priceHtml .= "<li><b>Количество</b> $price->count за <b>Цена</b> $price->price гривен;</li> <br>";
                    }
                    return $priceHtml .= "</ul>";
                },
                'headerOptions' => [
                    'width' => '400px',
                ]
            ],
            [
                'attribute' => 'count_report',
                'filter'    => false,
                'value' => function($model) {
                    return ProductFeedbackEntity::find()->where(['product_id' => $model->id])->count();
                },
                'headerOptions' => [
                    'width' => '100px',
                ]
            ],
            [
                'attribute' => 'created_at',
                'format'    => ['date', 'Y-MM-d'],
                'filter'    => DateRangePicker::widget([
                    'model'          => $modelProduct,
                    'attribute'      => 'product_created_range',
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
                'header'        => 'Действия',
                'template'      => '{view-product}&nbsp;&nbsp;&nbsp;{delete-product}',
                'buttons'       => [
                    'delete-product' => function($url, $model) {
                        return Html::a(('<span class="glyphicon glyphicon-trash"></span>'),
                            $url,
                            [
                                'title' => 'Удалить продукт'
                            ]
                        );
                    },
                    'view-product' => function ($url, $model) {
                        return Html::a(('<span class="glyphicon glyphicon-eye-open"></span>'),
                            $url,
                            [
                                'title' => 'Перейти на карточку товара'
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