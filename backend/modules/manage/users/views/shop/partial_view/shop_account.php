<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\daterange\DateRangePicker;

    /* @var $profile \common\models\shop_profile\ShopProfileEntity */

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>

<div id="category" class="tab-pane fade in active">
    <?php $formCategory = ActiveForm::begin([
        'options'                => ['enctype' => 'multipart/form-data'],
        'id'                     => 'dynamic-form',
        'enableClientValidation' => true
    ]); ?>

    <div class="row">
        <div class="col-xs-5">
            <?php
            if ($profile->category_start == null) {
                $profile->_categoryStart = Yii::$app->formatter->asDate(time(), 'php: d.m.Y');
            }
            ?>
            <label class="control-label">Выберите срок аккаунта магазина:</label>
            <div class="input-group drp-container">
                <?php echo DateRangePicker::widget([
                        'model'             => $profile,
                        'attribute'         => '_categoryDate',
                        'useWithAddon'      => true,
                        'convertFormat'     => true,
                        'startAttribute'    => '_categoryStart',
                        'endAttribute'      => '_categoryEnd',
                        'startInputOptions' => ['value' => $profile->_categoryStart],
                        'endInputOptions'   => ['value' => $profile->_categoryEnd],
                        'pluginOptions'     => [
                            'autoclose' => true,
                            'minDate'   => date('d.m.Y'),
                            'locale'    => ['format' => 'd.m.Y']
                        ]
                    ]) . $addon;
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-3">
            <br><?= Html::submitButton('Применить', ['class' => 'btn btn-success']); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
