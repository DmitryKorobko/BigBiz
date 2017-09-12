<?php

/* @var $this yii\web\View */
/* @var $product \common\models\product\ProductEntity */
/* @var $city \common\models\city\CityEntity */
/* @var $price \common\models\product_price\ProductPriceEntity */

$this->title = 'Обновить товар: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Список товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить продукт';
?>
<div class="personal-info-update">
    <?= $this->render('_form', [
        'product'  => $product,
        'city'     => $city,
        'price'    => $price
    ]); ?>
</div>