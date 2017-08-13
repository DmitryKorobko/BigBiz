<?php
use yii\{
    helpers\Html, widgets\ActiveForm,  web\View
};
use kartik\file\FileInput;
use common\models\admin_contact\AdminContactEntity;
use backend\models\BackendUserEntity;

/* @var $this View */
/* @var $modelProfile AdminContactEntity */
/* @var $user BackendUserEntity */

$this->title = 'Мой Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="personal-info-index">

    <div class="col-sm-3">
        <div class="row">
             <div class="col-xs-7 col-sm-12">
                 <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php $formProfile = ActiveForm::begin([
                            'id'                   => 'dynamic-form',
                            'action'               => ['manage-profile/index'],
                            'enableAjaxValidation' => true,
                            'validateOnChange'     => true,
                            'options'              => ['enctype' => 'multipart/form-data', 'validateOnSubmit' => true],
                        ]); ?>
                        <h3 class="panel-title">Фото профиля</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                            $image = '<img alt="Фото"
                                src="' . AdminContactEntity::getCurrentImage(@Yii::$app->user->identity->getId()) .
                                '" class="img-responsive" style="margin: auto">
                                <h5 style="text-align: center"><i class="fa fa-pencil-square-o"></i></h5>';
                            if (true) {
                                $layoutTemplates = ['main2' => '{preview} {remove} {browse}'];
                            } else {
                                $layoutTemplates = ['main2' => '{preview} {browse}'];
                            }
                        ?>
                        <div id="kv-avatar-errors" class="center-block" style="width:auto;display:none"></div>
                        <div class="kv-avatar center-block" style="width:auto">
                            <?= $formProfile->field($modelProfile, 'avatar')->widget(FileInput::className(), [
                                'pluginOptions' => [
                                    'overwriteInitial'      => true,
                                    'maxFileSize'           => 10000,
                                    'showClose'             => false,
                                    'showCaption'           => false,
                                    'showBrowse'            => false,
                                    'browseOnZoneClick'     => true,
                                    'removeLabel'           => '',
                                    'removeIcon'            => '<i class="glyphicon glyphicon-remove"></i>',
                                    'removeTitle'           => 'Cancel or reset changes',
                                    'elErrorContainer'      => '#kv-avatar-errors',
                                    'msgErrorClass'         => 'alert alert-block alert-danger',
                                    'defaultPreviewContent' => $image,
                                    'layoutTemplates'       => $layoutTemplates,
                                    'allowedFileExtensions' => ["jpg", "png", "gif"]
                                ]
                            ])->label(false);
                            ?>
                        </div>
                        <br><br>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    <div class="col-sm-9">

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#profile">Профиль</a></li>
        </ul>

        <div class="tab-content" style="background-color:#ffffff; border: 1px solid #f0f0f0; padding: 5px">
            <div id="profile" class="tab-pane fade in active">

                <?= $formProfile->field($modelProfile, 'nickname')->input('text'); ?>

                <div class="row">
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'viber', ['template' => "
                            <i class='fa fa-group'>&nbsp</i>{label}\n{input}\n{hint}\n{error}"])
                            ->input('text');
                        ?>
                    </div>

                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'skype', ['template' => "
                            <i class='fa fa-skype'>&nbsp</i>{label}\n{input}\n{hint}\n{error}"])
                            ->input('text');
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'telegram', ['template' => "
                            <i class='fa fa-telegram'>&nbsp</i>{label}\n{input}\n{hint}\n{error}"])
                            ->input('text');
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'vipole', ['template' => "
                            <i class='fa fa-shield'>&nbsp</i>{label}\n{input}\n{hint}\n{error}"])
                            ->input('text');
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'jabber', ['template' => "
                            <i class='fa fa-lightbulb-o'>&nbsp</i>{label}\n{input}\n{hint}\n{error}"])
                            ->input('text');
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Применить', ['class' => 'btn btn-success']); ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

        </div><!--/col-->
    </div>
</div>