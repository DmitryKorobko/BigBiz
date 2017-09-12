<?php

use yii\{
    grid\GridView, helpers\Html, helpers\Url, widgets\Pjax
};
use common\models\{
    city\CityEntity, product_feedback\ProductFeedbackEntity
};
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $models backend\modules\shop\control\controllers\ProductController */

$this->title = "Список товаров";

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'name',
        'filter'    => true,
        'headerOptions' => [
            'width' => '400px',
        ]
    ],
    [
        'header'        => 'Изображение',
        'format'        => 'raw',
        'content'       => function ($model) {
            $imagePath = Yii::getAlias('@webroot') . str_replace('/admin', '', $model->image);
            if ($model->image && file_exists($imagePath)) {
                return Html::img($model->image, ['style' => 'width:200px;']);
            } else {
                return Html::img(Url::toRoute('/images/default/no_image.png'), ['style' => 'width:200px;']);
            }
        },
        'headerOptions' => [
            'width' => '300px',
        ]
    ],
    [
        'attribute' => 'cities',
        'format'    => 'html',
        'filter'    => Html::activeDropDownList(
            $searchModel,
            'cities',
            ArrayHelper::map(CityEntity::find()->asArray()->all(), 'id', 'name'),
            ['class' => 'form-control', 'value' => Yii::$app->request->get('ProductEntity')['cities'], 'prompt'=>'']
        ),
        'value'     => function ($model) {
            $cityHtml = "<ul>";
            foreach ($model->cities as $city) {
                $cityHtml .= "<li>$city->name</li> <br>";
            }
            return $cityHtml .= "</ul>";
        },
        'headerOptions' => [
            'width' => '250px',
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
            'width' => '450px',
        ]
    ],
    [
        'attribute' => 'availability',
        'label'     => 'В наличии',
        'filter'    => ['Нет', 'Да'],
        'format'    => 'boolean'
    ],
    [
        'attribute' => 'count_report',
        'filter'    => false,
        'value' => function($model) {
            return ProductFeedbackEntity::find()->where(['product_id' => $model->id])->count();
        }
    ],
    [
        'attribute' => 'count_new_report',
        'filter'    => false,
        'value' => function($model) {
            $conditionTime = time() - 86400;
            return ProductFeedbackEntity::find()->where(['and', "product_id = $model->id",
                "created_at >= $conditionTime"])->count();
        }
    ],
    [
        'class'         => 'yii\grid\ActionColumn',
        'header'        => 'Действия',
        'template'      => '{view}&nbsp;&nbsp;&nbsp;{set-availability}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
        'buttons'       => [
            'view' => function ($url, $model) {
                return Html::a(('<span class="glyphicon glyphicon-eye-open"></span>'),
                    '/../main/shop-profile/product?id='. $model->id,
                    [
                        'title'     => 'Перейти на карточку товара',
                        'target'    => '_blank',
                        'data-pjax' => '0'

                    ]);
            },
            'set-availability' => function ($url, $model) {
                /* @var $model common\models\product\ProductEntity */
                return Html::a(($model->getAvailability()) ? ('<span class="glyphicon glyphicon-check"></span>')
                    : '<span class="glyphicon glyphicon-unchecked"></span>',
                    $url,
                    [
                        'title' => ($model->getAvailability()) ? 'Сделать не доступным' : 'Активировать'
                    ]);
            }
        ],
        'headerOptions' => [
            'width' => '100px',
        ]
    ]
];
?>

<div>
    <div class="form-group">
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Очистить фильтры', ['index'], ['class' => 'btn btn-primary pull-right']) ?>
    </div>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>