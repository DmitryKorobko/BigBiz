<?php
use yii\{
    widgets\Pjax, grid\GridView
};

/* @var $dataProviderFeedback \yii\data\ActiveDataProvider */
?>

<div id="feedbacks" class="tab-pane fade">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderFeedback,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name'
            ],
            [
                'attribute' => 'message',
                'format' => 'html'
            ],
            [
                'attribute' => 'created_at',
                'format'    => ['date', 'php:F j, Y']
            ],
            [
                'class'         => 'yii\grid\ActionColumn',
                'header'        => 'Действия',
                'template'      => '{delete-feedback}',
                'buttons'       => [
                    'delete-feedback' => function($url, $model) {
                        return \yii\helpers\Html::a(('<span class="glyphicon glyphicon-trash"></span>'),
                            $url,
                            [
                                'title' => 'Удалить запись'
                            ]
                        );
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
