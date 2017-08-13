<?php

use yii\{
    helpers\Html, widgets\ActiveForm, helpers\ArrayHelper
};
use kartik\select2\Select2;
use common\models\main_category_section\MainCategorySectionEntity;

/**
 * @var $this yii\web\View
 * @var $category \common\models\main_category_section\MainCategorySectionEntity
 * @var $form yii\widgets\ActiveForm
 */
?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'dynamic-form']); ?>

    <?= $form->field($category, 'name')->input('text'); ?>

    <?= $form->field($category, 'description')->input('text'); ?>

    <?= $form->field($category, 'parent_category_id')->widget(Select2::className(), [
        'data'          => ArrayHelper::map(MainCategorySectionEntity::find()->all(), 'id', 'name'),
        'maintainOrder' => true,
        'options'       => ['placeholder' => 'Выберите родительскую категорию ...', 'multiple' => false],
        'pluginOptions' => ['allowClear' => false]
    ]); ?>

    <?= $form->field($category, 'permissions_only_admin')->dropDownList([0 => 'Нет', 1 => 'Да']); ?>

    <?= $form->field($category, 'sort')->input('number'); ?>

    </br>
    <div class="form-group">
        <?php
            $method = $category->isNewRecord ? 'Создать' : 'Обновить';
            $submitClass = $category->isNewRecord ? 'btn btn-success' : 'btn btn-primary';
        ?>

        <?= Html::submitButton($method, ['class' => $submitClass]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
