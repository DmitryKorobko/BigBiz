<?php

/* @var $this yii\web\View */
/* @var $product \common\models\product\ProductEntity */
/* @var $city \common\models\city\CityEntity */
/* @var $price \common\models\product_price\ProductPriceEntity */

$this->title = 'Добавить товар';
$this->params['breadcrumbs'][] = ['label' => 'Список товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-create">


    <?= $this->render('_form', [
        'product'  => $product,
        'city'     => $city,
        'price'    => $price
    ]) ?>

</div>
