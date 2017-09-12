<?php
use kartik\file\FileInput;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use \xvs32x\tinymce\Tinymce;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $product \common\models\product\ProductEntity
 * @var $city \common\models\city\CityEntity
 * @var $price \common\models\product_price\ProductPriceEntity
 * @var $delivery \common\models\product_delivery\ProductDeliveryEntity
 * @var $form yii\widgets\ActiveForm
 */
$userId = Yii::$app->user->identity->getId();
?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'dynamic-form']); ?>

    <?= $form->field($product, 'name')->input('text'); ?>

    <?=
        $form->field($product, 'description')->widget(Tinymce::className(), [
            'pluginOptions'      => [
                'height' => 300,
                'plugins'           => [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools codesample responsivefilemanager'
                ],
                'toolbar1'          => 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                'toolbar2'          => "| print preview | responsivefilemanager | link unlink anchor | forecolor backcolor emoticons | codesample",
                'image_advtab'      => true,
                'filemanager_title' => "Filemanager",
                'language'          => 'ru',
                'setup' => new \yii\web\JsExpression("function(editor){
                        editor.on('change', function () {
                        editor.save();
                        });
                }"),
            ],
            'fileManagerOptions' => [
                'configPath' => [
                    'upload_dir'       => "/admin/images/uploads/user-{$userId}/filemanager/source/",
                    'current_path'     => "../../../images/uploads/user-{$userId}/filemanager/source/",
                    'thumbs_base_path' => "../../../images/uploads/user-{$userId}/filemanager/thumbs/"
                ]
            ]
        ]);
    ?>

    <?= $form->field($product, 'towns')->widget(Select2::classname(), [
        'value'         => array_keys(ArrayHelper::map($product->cities, 'id', 'name')),
        'data'          => ArrayHelper::map($city->find()->all(), 'id', 'name'),
        'maintainOrder' => true,
        'options'       => [
            'placeholder' => 'Выберите город распространения ...',
            'multiple'    => false
        ],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]); ?>
    <br/>
    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-usd"></i> Цены</h4></div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody'      => '.container-items',
                'widgetItem'      => '.item',
                'limit'           => 4,
                'min'             => 1,
                'insertButton'    => '.add-item',
                'deleteButton'    => '.remove-item',
                'model'           => $price[0],
                'formId'          => 'dynamic-form',
                'formFields'      => [
                    'count',
                    'price',
                    'price_usd',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
                <?php foreach ($price as $i => $value): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left"></h3>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            // necessary for update action.
                            if (!$value->isNewRecord) {
                                echo Html::activeHiddenInput($value, "[{$i}]id");
                            }
                            ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?= $form->field($value, "[{$i}]count")->textInput([
                                        'maxlength' => true,
                                        'required'  => true
                                    ]) ?>
                                </div>
                                <div class="col-sm-4">
                                    <?= $form->field($value, "[{$i}]price")->textInput([
                                        'maxlength' => true,
                                        'required'  => false
                                    ]) ?>
                                </div>
                                <div class="col-sm-4">
                                    <?= $form->field($value, "[{$i}]price_usd")->textInput([
                                        'maxlength' => true,
                                        'required'  => false
                                    ]) ?>
                                </div>
                            </div><!-- .row -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
    <?= $form->field($product, 'image')->widget(FileInput::className(), [
        'pluginOptions' => [
            'showUpload'            => false,
            'initialPreview'        => ($product->image) ? [
                Html::img($product->image, ['maxWidth' => 300, 'height' => 200])
            ] : false,
            'initialCaption'        => (!$product->image) ? "Выберите изображение ..." : null,
            'overwriteInitial'      => true,
            'showRemove'            => false,
            'allowedFileExtensions' => ['jpg', 'jpeg', 'png']
        ]
    ]);
    ?>

    <?= $form->field($product, 'sort')->input('number'); ?>

    <div class="form-group">
        <?php
        $method = $product->isNewRecord ? 'Создать' : 'Обновить';
        $submitClass = $product->isNewRecord ? 'btn btn-success' : 'btn btn-primary';
        ?>

        <?= Html::submitButton($method, ['class' => $submitClass]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
