<?php
use yii\{
    helpers\Html, widgets\Pjax, grid\GridView
};
use common\models\user_profile\UserProfileEntity;

/* @var $productFeedbackProvider \yii\data\ActiveDataProvider */
/* @var $productFeedback \common\models\product_feedback\ProductFeedbackEntity */
?>
<div id="product-reviews" class="tab-pane fade">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $productFeedbackProvider,
        'filterModel'  => $productFeedback,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'format'    => 'html',
                'filter'    => false,
                'value' => function($model) {
                    $profile = UserProfileEntity::find()->where(['user_id' => $model->user_id])->asArray()->one();
                    $profile['avatar'] = UserProfileEntity::getCurrentImage($profile['user_id']);
                    return "<div class='col-sm-4'>
                                    <img src='" . $profile['avatar'] . "' class='img-circle img-responsive' width='50%'>
                                    <div class='review-block-name'><a href='#'>" . $profile['nickname'] . "</a></div>
                                    <div class='review-block-date'>" . date('Y-m-d H:i:s', $model->created_at) .
                        "<br/>1 day ago</div>
                                </div>";
                }
            ],
            [
                'attribute' => 'rating',
                'label'     => 'Общая оценка',
                'format'    => 'html',
                'filter'    => false,
                'value' => function($model) {
                    return "<div class='col-sm-4'>                                    
                                   <div class='review-block-text'>$model->rating</div>                             
                                </div>";
                }
            ],
            [
                'attribute' => 'text',
                'label'     => 'Сообщение',
                'format'    => 'html',
                'filter'    => false,
                'value' => function($model) {
                    return "<div class='col-sm-12'>                                    
                                   <div class='review-block-text'>$model->text</div>                             
                                </div>";
                }
            ],
            [
                'class'         => 'yii\grid\ActionColumn',
                'header'        => 'Действия',
                'template'      => '{delete-product-feedback}',
                'buttons'       => [
                    'delete-product-feedback' => function($url) {
                        return Html::a(('<span class="glyphicon glyphicon-trash"></span>'),
                            $url,
                            [
                                'title' => 'Удалить отзыв'
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