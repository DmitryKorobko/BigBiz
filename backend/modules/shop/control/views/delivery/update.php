<?php

/* @var $this yii\web\View */
/* @var $delivery \common\models\product_delivery\ProductDeliveryEntity */
/* @var $product \common\models\product\ProductEntity */
/* @var $products \common\models\product\ProductEntity */

$this->title = 'Обновить адрес доставки товара';
$this->params['breadcrumbs'][] = ['label' => 'Адреса доставки товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-update">
    <?= $this->render('_form', [
        'delivery' => $delivery,
        'product'  => $product,
        'products' => $products
    ]) ?>
</div>
