<?php

use kartik\daterange\DateRangePicker;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $settings \common\models\settings\SettingsEntity
 * @var $form yii\widgets\ActiveForm
 */

?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'dynamic-form']); ?>

    <?php
        $readonly = $settings->isNewRecord ? false : true;
        echo $form->field($settings, 'key')->input('text', ['readonly' => $readonly]);
    ?>

    <?= $form->field($settings, 'value')->input('text'); ?>

    </br>
    <div class="form-group">
        <?php
        $method = $settings->isNewRecord ? 'Создать' : 'Обновить';
        $submitClass = $settings->isNewRecord ? 'btn btn-success' : 'btn btn-primary';
        ?>

        <?= Html::submitButton($method, ['class' => $submitClass]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
