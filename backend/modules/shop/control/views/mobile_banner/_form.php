<?php

use kartik\daterange\DateRangePicker;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * @var $this yii\web\View
 * @var $banner \common\models\mobile_banner\MobileBannerEntity
 * @var $form yii\widgets\ActiveForm
 */

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'dynamic-form']); ?>

    <?= $form->field($banner, 'image')->widget(FileInput::className(), [
        'pluginOptions' => [
            'showUpload'            => false,
            'initialPreview'        => ($banner->image) ? [
                Html::img($banner->image, ['width' => 300])
            ] : false,
            'initialCaption'        => (!$banner->image) ? "Выберите изображение ..." : null,
            'overwriteInitial'      => true,
            'showRemove'            => false,
            'allowedFileExtensions' => ['jpg', 'jpeg', 'png']
        ]
    ]);
    ?>
    <label class="control-label">Выберите период времени отображения баннера:</label>
    <div class="input-group drp-container">
        <?= DateRangePicker::widget([
            'model'             => $banner,
            'attribute'         => 'period_of_time',
            'useWithAddon'      => true,
            'convertFormat'     => true,
            'startAttribute'    => 'start_date',
            'endAttribute'      => 'end_date',
            'readonly'          => 'true',
            'startInputOptions' => ['value' => $banner->start_date],
            'endInputOptions'   => ['value' =>  $banner->end_date],
            'pluginOptions'     => [
                'autoclose' => true,
                'minDate'   => date('Y-m-d'),
                'locale'    => ['format' => 'Y-m-d'],
            ],
        ]) . $addon;
        ?>
    </div>

    </br>
    <div class="form-group">
        <?php
        $method = $banner->isNewRecord ? 'Создать' : 'Обновить';
        $submitClass = $banner->isNewRecord ? 'btn btn-success' : 'btn btn-primary';
        ?>

        <?= Html::submitButton($method, ['class' => $submitClass]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
