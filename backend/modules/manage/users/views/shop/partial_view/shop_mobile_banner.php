<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $dataProviderMobileBanner \yii\data\ActiveDataProvider */
?>

<div id="mobile_banner" class="tab-pane fade">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderMobileBanner,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header'  => 'Изображение',
                'format'  => 'raw',
                'content' => function ($model) {
                    $imagePath = Yii::getAlias('@webroot') . str_replace('/admin', '', $model->image);
                    if ($model->image && file_exists($imagePath)) {
                        return Html::img($model->image, ['style' => 'width:200px;']);
                    } else {
                        return Html::img(\yii\helpers\Url::toRoute('/images/default/no_image.png'),
                            ['style' => 'width:200px;']);
                    }
                }
            ],
            [
                'attribute' => 'start_date',
                'format'    => ['date', 'php:j F, Y']
            ],
            [
                'attribute' => 'end_date',
                'format'    => ['date', 'php:j F, Y']
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
                'template'      => '{turn-on-mobile-banner}',
                'buttons'       => [
                    'turn-on-mobile-banner' => function ($url, $model) {
                        return Html::a(($model->status) ? ('<span class="glyphicon glyphicon-check"></span>')
                            : '<span class="glyphicon glyphicon-unchecked"></span>',
                            $url,
                            [
                                'title' => ($model->status) ? 'Выключить баннер' : 'Включить баннер'
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
