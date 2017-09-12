<?php
use yii\{
    helpers\Html, widgets\Pjax, grid\GridView, helpers\ArrayHelper
};
use backend\models\BackendUserEntity;
use common\models\{
    shop_profile\ShopProfileEntity, user_profile\UserProfileEntity
};
use kartik\rating\StarRating;

/* @var $shopFeedbackProvider \yii\data\ActiveDataProvider */
/* @var $shopFeedback \common\models\shop_feedback\ShopFeedbackEntity */
?>
<div id="reviews" class="tab-pane fade">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $shopFeedbackProvider,
        'filterModel'  => $shopFeedback,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_by',
                'format'    => 'html',
                'filter'    => Html::activeDropDownList(
                    $shopFeedback,
                    'created_by',
                    ArrayHelper::map(BackendUserEntity::find()->where(['status' => BackendUserEntity::STATUS_VERIFIED])->asArray()->all(), 'id', 'email'),
                    ['class' => 'form-control', 'value' => Yii::$app->request->get('ShopFeedbackEntity')['created_by'], 'prompt' => '']
                ),
                'value' => function($model) {
                    $roles = Yii::$app->authManager->getRolesByUser($model->created_by);
                    if (isset($roles['shop'])) {
                        $profile = ShopProfileEntity::find()->select('user_id, name as nickname, image as avatar')
                            ->where(['user_id' => $model->created_by])->asArray()->one();
                        $profile['avatar'] = ShopProfileEntity::getCurrentImage($profile['user_id']);
                    } else {
                        $profile = UserProfileEntity::find()->where(['user_id' => $model->created_by])->asArray()->one();
                        $profile['avatar'] = UserProfileEntity::getCurrentImage($profile['user_id']);
                    }
                    return "<div class='col-sm-3'>
                                    <img src='" . $profile['avatar'] . "' class='img-circle img-responsive' width='50%'>
                                    <div class='review-block-name'><a href='#'>" . $profile['nickname'] . "</a></div>
                                    <div class='review-block-date'>" . date('Y-m-d H:i:s', $model->created_at) . "<br/>1 day ago</div>
                                </div>";
                }
            ],
            [
                'attribute' => 'average_rating',
                'label'     => 'Отзыв по категориям',
                'format'    => 'raw',
                'value' => function($model) {
                    return   (
                        ('Качество товара'. "<br/>" . StarRating::widget([
                                'model' => $model,
                                'name' => 'average_rating',
                                'value' => $model->product_rating,
                                'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                        )
                        . ('Работа оператора'. "<br/>" . StarRating::widget([
                                'model' => $model,
                                'name' => 'average_rating',
                                'value' => $model->operator_rating,
                                'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                        )
                        . ('Надежность'. "<br/>" . StarRating::widget([
                                'model' => $model,
                                'name' => 'average_rating',
                                'value' => $model->reliability_rating,
                                'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                        )
                        . ('Качество доставки'. "<br/>" . StarRating::widget([
                                'model' => $model,
                                'name' => 'average_rating',
                                'value' => $model->marker_rating,
                                'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                        )
                    );
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