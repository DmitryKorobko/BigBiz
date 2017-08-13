<?php
use yii\{
    widgets\Pjax, grid\GridView
};
use backend\models\BackendUserEntity;

/* @var $dataProviderStatistics \yii\data\ActiveDataProvider */
?>

<div id="statistics" class="tab-pane fade">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderStatistics,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_at',
                'header'    => 'Зарегистрирован',
                'format'    => ['date', 'Y-MM-d H:i:s'],
                'filter'    => false
            ],
            [
                'attribute' => 'countThemes',
                'header'    => 'Темы',
                'value'     => function($model) {
                    $moder = new BackendUserEntity();
                    return ($moder->getActivity($model['id']))['countThemes'];
                }
            ],
            [
                'attribute' => 'countComments',
                'header'    => 'Комментарии',
                'value'     => function($model) {
                    $moder = new BackendUserEntity();
                    return ($moder->getActivity($model['id']))['countComments'];
                }
            ],
            [
                'attribute' => 'countMessages',
                'header'    => 'Сообщения',
                'value'     => function($model) {
                    $moder = new BackendUserEntity();
                    return ($moder->getActivity($model['id']))['countMessages'];
                }
            ],
            [
                'attribute' => 'countShopReviews',
                'header'    => 'Отзывы о магазинах',
                'value'     => function($model) {
                    $moder = new BackendUserEntity();
                    return ($moder->getActivity($model['id']))['countShopReviews'];
                }
            ],
            [
                'attribute' => 'countProductReviews',
                'header'    => 'Отзывы о товарах',
                'value'     => function($model) {
                    $moder = new BackendUserEntity();
                    return ($moder->getActivity($model['id']))['countProductReviews'];
                }
            ],
            [
                'attribute' => 'reputation',
                'header'    => 'Репутация',
                'value'     => function($model) {
                    $moder = new BackendUserEntity();
                    return ($moder->getReputation($model['id']));
                }
            ],
            [
                'attribute' => 'lastVisitTime',
                'header'    => 'Был на сайте',
                'format'    => ['date', 'Y-MM-d H:i:s'],
                'value'     => function($model) {
                    $moder = new BackendUserEntity();
                    return ($moder->getLastVisitTime($model['id']));
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
