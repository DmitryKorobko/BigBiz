<?php

use yii\{
    helpers\Html, widgets\ActiveForm
};
use backend\models\BackendUserEntity;

/* @var $this yii\web\View */
/* @var $model \backend\models\BackendUserEntity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'email')->input('text', ['maxlength' => true]); ?>

    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true, 'value' => '']); ?>

    <?= $form->field($model, 'confirm')->passwordInput(['maxlength' => true]); ?>

    <input id="role" name="RegistrationForm[role]" value="<?= BackendUserEntity::ROLE_ADMIN ?>" readonly hidden/>


    <div class="form-group">
        <?php
        $method = $model->isNewRecord ? 'Создать' : 'Обновить';
        $submitClass = $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary';
        ?>

        <?= Html::submitButton($method, ['class' => $submitClass]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
