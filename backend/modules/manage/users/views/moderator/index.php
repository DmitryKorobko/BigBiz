<?php
use yii\{
    helpers\Html, widgets\Pjax, grid\GridView
};
use backend\models\BackendUserEntity;
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $models backend\modules\manage\users\controllers\ModeratorController
 * @var $searchModel backend\modules\manage\users\models\ShopSearch
 */

$this->title = 'Модераторы сайта';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'email',
    [
        'attribute' => 'countThemes',
        'value'     => function($model) {
            $moder = new BackendUserEntity();
            return ($moder->getActivity($model['id']))['countThemes'];
        }
    ],
    [
        'attribute' => 'countComments',
        'value'     => function($model) {
            $moder = new BackendUserEntity();
            return ($moder->getActivity($model['id']))['countComments'];
        }
    ],
    [
        'attribute' => 'created_at',
        'format'    => ['date', 'Y-MM-d H:i:s'],
        'filter' => false
    ],
    [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{permit}&nbsp;&nbsp;{delete}'
    ],
];

$models = $dataProvider->allModels;
$dataProvider->allModels = [];
foreach ($models as $model) {
    $dataProvider->allModels[$model['id']] = $model;
}

?>
<div class="personal-info-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form-group">
        <?= Html::a('Создать модератора', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>

</div>
