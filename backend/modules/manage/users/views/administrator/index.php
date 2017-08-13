<?php
use yii\{
    helpers\Html, widgets\Pjax, grid\GridView
};

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $models backend\modules\manage\users\controllers\AdministratorController
 * @var $searchModel backend\modules\manage\users\models\ShopSearch
 */

$this->title = 'Администраторы сайта';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    'email',
    [
        'attribute' => 'created_at',
        'format'    => ['date', 'Y-MM-d H:i:s'],
        'filter' => false
    ],
    [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{update}&nbsp;&nbsp;{permit}&nbsp;&nbsp;{delete}'
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
        <?= Html::a('Создать администратора', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>

</div>
