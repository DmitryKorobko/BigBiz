<?php

use yii\{
    helpers\Html, widgets\ActiveForm, helpers\ArrayHelper
};
use xvs32x\tinymce\Tinymce;
use kartik\{
    file\FileInput, select2\Select2
};
use common\models\child_category_section\ChildCategorySectionEntity;

/* @var $this yii\web\View */
/* @var $theme \common\models\theme\ThemeEntity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($theme, 'category_id')->widget(Select2::className(), [
        'data'          => ArrayHelper::map(ChildCategorySectionEntity::find()->all(), 'id', 'name'),
        'maintainOrder' => true,
        'options'       => ['placeholder' => 'Выберите категорию ...', 'multiple' => false],
        'pluginOptions' => ['allowClear' => false]
    ]); ?>

    <?= $form->field($theme, 'name')->input('text'); ?>

    <?=
        $form->field($theme, 'description')->widget(Tinymce::className(), [
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
                    'upload_dir'       => '/admin/images/uploads/admin/filemanager/source/',
                    'current_path'     => '../../../images/uploads/admin/filemanager/source/',
                    'thumbs_base_path' => '../../../images/uploads/admin/filemanager/thumbs/'
                ]
            ]
        ]);
    ?>

    <?=
    $form->field($theme, 'image')->widget(FileInput::className(), [
        'pluginOptions' => [
            'showUpload'            => false,
            'initialPreview'        => ($theme->image) ? [
                Html::img($theme->image, ['width' => 800])
            ] : false,
            'initialCaption'        => (!$theme->image) ? "Выберите изображение ..." : null,
            'overwriteInitial'      => true,
            'showRemove'            => false,
            'allowedFileExtensions' => ['jpg', 'jpeg', 'png']
        ]
    ]);
    ?>

    <?= $form->field($theme, 'sort')->input('number'); ?>

    <div class="form-group">
        <?php
        $method = $theme->isNewRecord ? 'Создать' : 'Обновить';
        $submitClass = $theme->isNewRecord ? 'btn btn-success' : 'btn btn-primary';
        ?>

        <?= Html::submitButton($method, ['class' => $submitClass]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
