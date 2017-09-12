<?php
use yii\{
    helpers\Html, widgets\Pjax, grid\GridView
};
use common\models\user_profile\UserProfileEntity;

/* @var $shopFeedbackProvider \yii\data\ActiveDataProvider */
/* @var $shopFeedback \common\models\shop_feedback\ShopFeedbackEntity */
?>
<div id="shop-reviews" class="tab-pane fade">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $shopFeedbackProvider,
        'filterModel'  => $shopFeedback,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_by',
                'format'    => 'html',
                'filter'    => false,
                'value' => function($model) {
                        $profile = UserProfileEntity::find()->where(['user_id' => $model->created_by])
                            ->asArray()->one();
                        $profile['avatar'] = UserProfileEntity::getCurrentImage($profile['user_id']);
                    return "<div class='col-sm-3'>
                                    <img src='" . $profile['avatar'] . "' class='img-circle img-responsive' width='50%'>
                                    <div class='review-block-name'><a href='#'>" . $profile['nickname'] . "</a></div>
                                    <div class='review-block-date'>" . date('Y-m-d H:i:s', $model->created_at) .
                        "<br/>1 day ago</div>
                                </div>";
                }
            ],
            [
                'attribute' => 'average_rating',
                'label'     => 'Отзыв по категориям',
                'format'    => 'html',
                'value' => function($model) {
                    return "<div class='col-sm-9'>
                                    <div class='review-block-title'>Качество товара
                                        <input type='number' class='rating' min=0 max=5 data-size='xs'
                                               value=" . $model->product_rating . " readonly data-show-clear='false'
                                               data-show-caption='false'>
                                    </div>

                                    <div class='review-block-title'>Работа оператора
                                        <input type='number' class='rating' min=0 max=5 data-size='xs'
                                               value=" . $model->operator_rating . " readonly data-show-clear='false' 
                                               data-show-caption='false'>
                                    </div>

                                    <div class='review-block-title'>Надежность
                                        <input type='number' class='rating' min=0 max=5 data-size='xs'
                                               value=" . $model->reliability_rating . " readonly data-show-clear='false' 
                                               data-show-caption='false'>
                                    </div>

                                    <div class='review-block-title'>Качество доставки
                                        <input type='number' class='rating' min=0 max=5 data-size='xs'
                                               value=" . $model->marker_rating . " readonly data-show-clear='false' 
                                               data-show-caption='false'>
                                    </div>
                                </div>";
                }
            ],
            [
                'class'         => 'yii\grid\ActionColumn',
                'header'        => 'Действия',
                'template'      => '{delete-shop-feedback}',
                'buttons'       => [
                    'delete-shop-feedback' => function($url) {
                        return Html::a(('<span class="glyphicon glyphicon-trash"></span>'),
                            $url,
                            [
                                'title' => 'Удалить отзыв',
                                'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?'
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