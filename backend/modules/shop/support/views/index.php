<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \xvs32x\tinymce\Tinymce;
use kartik\select2\Select2;
use common\models\feedback\Feedback;

/* @var $this yii\web\View */
/* @var $feedback \common\models\feedback\Feedback.php */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Здесь Вы можете `Сообщить об ошибке` или `Оставить пожелания`";
$userId = Yii::$app->user->identity->getId();
?>

<div class="personal-info-form" xmlns="http://www.w3.org/1999/html">
    <h3><?= $this->title; ?></h3>
    <br/>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($feedback, 'name')->input('text'); ?>

    <?= $form->field($feedback, 'cause_send')->widget(Select2::className(), [
        'data'          => [
            Feedback::APPLICATION_PROBLEM => 'Ошибка работы приложения',
            Feedback::FUNCTIONAL_PROBLEM  => 'Недостаточная функциональность',
            Feedback::WISHES              => 'Другая проблема или пожелание'
        ],
        'maintainOrder' => true,
        'options'       => ['placeholder' => 'Выберите причину отправки ...', 'multiple' => false],
        'pluginOptions' => ['allowClear' => false]
    ]); ?>

    <?=
        $form->field($feedback, 'message')->widget(Tinymce::className(), [
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
                'relative_urls' => false
            ],
            'fileManagerOptions' => [
                'configPath' => [
                    'upload_dir'       => "/admin/images/uploads/user-{$userId}/filemanager/source/",
                    'current_path'     => "../../../images/uploads/user-{$userId}/filemanager/source/",
                    'thumbs_base_path' => "../../../images/uploads/user-{$userId}/filemanager/thumbs/",
                ]
            ]
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
