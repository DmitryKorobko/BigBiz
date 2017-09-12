<?php
use yii\{
    helpers\Html, widgets\ActiveForm, web\View, grid\GridView, helpers\Url, helpers\ArrayHelper
};
use kartik\file\FileInput;
use backend\models\BackendUserEntity;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use common\models\{
    child_category_section\ChildCategorySectionEntity, theme\ThemeEntity, admin_contact\AdminContactEntity,
    feedback\Feedback
};

/* @var $this View */
/* @var $modelProfile AdminContactEntity */
/* @var $user BackendUserEntity */
/* @var $theme \common\models\theme\ThemeEntity */
/* @var $themeProvider ActiveDataProvider */

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
            <li><a data-toggle="tab" href="#themes">Темы</a></li>
            <li><a data-toggle="tab" href="#reviews">Отзывы</a></li>
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

            <div id="themes" class="tab-pane fade">
                <?php Pjax::begin(); ?>

                <?= GridView::widget([
                        'dataProvider' => $themeProvider,
                        'filterModel'  => $theme,
                        'columns'      => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'name',
                                'value'     => function ($model) {
                                    return $model->name;
                                }
                            ],
                            [
                                'header'  => 'Изображение',
                                'format'  => 'raw',
                                'content' => function ($model) {
                                    $imagePath = Yii::getAlias('@webroot') . str_replace('/admin', '', $model->image);
                                    if ($model->image && file_exists($imagePath)) {
                                        return Html::img($model->image, [ 'style' => 'width:200px;' ]);
                                    } else {
                                        return Html::img(Url::toRoute('/images/default/no_image.png'), [ 'style' => 'width:200px;' ]);
                                    }
                                },
                                'headerOptions' => [
                                    'width' => '300px',
                                ]
                            ],
                            [
                                'label'     => 'Категория темы',
                                'attribute' => 'category_id',
                                'format'    => 'html',
                                'filter'    => Html::activeDropDownList(
                                    $theme,
                                    'category_id',
                                    ArrayHelper::map(ChildCategorySectionEntity::find()->all(), 'id', 'name'),
                                    ['class' => 'form-control', 'value' => Yii::$app->request->get('ThemeEntity')['category_id'], 'prompt' => '']
                                ),
                                'value'     => function ($model) {
                                    $category = ChildCategorySectionEntity::findOne(['id' => $model->category_id ]);
                                    if ($category) {
                                        return $category->name;
                                    }
                                },
                                'headerOptions' => [
                                    'width' => '350px',
                                ]
                            ],
                            [
                                'attribute' => 'status',
                                'filter'    => [
                                    ThemeEntity::STATUS_VERIFIED   => 'Опубликована',
                                    ThemeEntity::STATUS_UNVERIFIED => 'На проверке'
                                ],
                                'value' => function ($model) {
                                    return ($model->status === ThemeEntity::STATUS_VERIFIED) ? 'Опубликована' : 'На проверке';
                                },
                                'headerOptions' => [
                                    'width' => '50px',
                                ]
                            ],
                            [
                                'attribute' => 'date_of_publication',
                                'value'     => function ($model) {
                                    return Yii::$app->formatter->asDate($model->date_of_publication);
                                }
                            ]
                        ]
                ]); ?>
                <?php Pjax::end(); ?>
            </div>

            <div id="reviews" class="tab-pane fade">
                <?php Pjax::begin(); ?>

                <?= GridView::widget([
                    'dataProvider' => $reviewProvider,
                    'filterModel'  => $review,
                    'columns'      => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'name',
                            'value'     => function ($model) {
                                return $model->name;
                            }
                        ],
                        [
                            'attribute' => 'author_name',
                            'header'    => 'Автор',
                            'value'     => function ($model) {
                                return $model->getReviewAuthorName($model->user_id);
                            }
                        ],
                        [
                            'attribute' => 'cause_send',
                            'value'     => function ($model) {
                                if ($model->cause_send === Feedback::APPLICATION_PROBLEM) {
                                    return 'Ошибка работы приложения';
                                } elseif ($model->cause_send === Feedback::FUNCTIONAL_PROBLEM) {
                                    return 'Недостаточная функциональность';
                                }

                                return 'Другая проблема';
                            },
                            'filter'    => [
                                Feedback::APPLICATION_PROBLEM => 'Ошибка работы приложения',
                                Feedback::FUNCTIONAL_PROBLEM  => 'Недостаточная функциональность',
                                Feedback::WISHES              => 'Другая проблема'
                            ]
                        ],
                        [
                            'attribute' => 'message',
                            'value'     => function ($model) {
                                return $model->message;
                            }
                        ]
                    ]
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
</div>