<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var $this yii\web\View
 * @var $delivery \common\models\product_delivery\ProductDeliveryEntity
 * @var $product \common\models\product\ProductEntity
 * @var $products \common\models\product\ProductEntity
 * @var $form yii\widgets\ActiveForm
 */

?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'dynamic-form']); ?>

    <?= $form->field($delivery, 'address')->input('text'); ?>

    <?= $form->field($delivery, 'product_id')->widget(Select2::classname(), [
        'value'         => array_keys(ArrayHelper::map($products, 'name', 'id')),
        'data'          => ArrayHelper::map($products, 'id', 'name'),
        'maintainOrder' => true,
        'options'       => [
            'placeholder' => 'Выберите товар',
            'multiple'    => false
        ],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]); ?>

</div>

    </br>
    <div class="form-group">
        <?php
        $method = $delivery->isNewRecord ? 'Создать' : 'Обновить';
        $submitClass = $delivery->isNewRecord ? 'btn btn-success' : 'btn btn-primary';
        ?>

        <?= Html::submitButton($method, ['class' => $submitClass]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
