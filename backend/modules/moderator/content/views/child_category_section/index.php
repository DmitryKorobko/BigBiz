<?php

use yii\{
    grid\GridView, helpers\Html, widgets\Pjax, helpers\ArrayHelper
};
use common\models\main_category_section\MainCategorySectionEntity;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\moderator\content\controllers\ChildCategorySectionController; */

$this->title = "Категории разделов второго уровня";

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'name'
    ],
    [
        'attribute' => 'description'
    ],
    [
        'label'     => 'Родительская категория',
        'attribute' => 'parent_category_id',
        'format'    => 'html',
        'filter'    => Html::activeDropDownList(
            $searchModel,
            'parent_category_id',
                ArrayHelper::map(MainCategorySectionEntity::find()->asArray()->all(), 'id', 'name'),
            [
                'class' => 'form-control',
                'value' => Yii::$app->request->get('ChildCategorySectionEntity')['parent_category_id'],
                'prompt' => ''
            ]
        ),
        'value'     => function ($model) {
            $mainCategory = MainCategorySectionEntity::findOne(['id' => $model->parent_category_id]);
            return  $mainCategory->name;
        }
    ],
    [
        'attribute' => 'permissions_only_admin',
        'filter' => false,
        'value' => function ($model) {
            return ($model->permissions_only_admin) ? 'Да' : 'Нет';
        },
        'headerOptions' => [
            'width' => '250px',
        ]
    ],
    [
        'attribute' => 'sort',
        'filter' => false
    ],
    [
        'class'         => 'yii\grid\ActionColumn',
        'header'        => 'Действия',
        'template'      => '{update}&nbsp;&nbsp;&nbsp;{delete}',
        'headerOptions' => [
            'width' => '100px',
        ]
    ]
];
?>

<div>
    <div class="form-group">
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>