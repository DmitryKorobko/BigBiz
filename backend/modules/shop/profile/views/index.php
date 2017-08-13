<?php
use yii\{
    helpers\Html, helpers\ArrayHelper, widgets\ActiveForm, grid\GridView, widgets\Pjax, data\ActiveDataProvider,
    web\View
};
use kartik\{
    select2\Select2, time\TimePicker, switchinput\SwitchInput, file\FileInput, rating\StarRating
};
use common\models\{
    city\CityEntity, shop_profile\ShopProfileEntity, user_profile\UserProfileEntity,
    shop_confidentiality\ShopConfidentialityEntity, shop_notifications_settings\ShopNotificationsSettingsEntity,
    shop_feedback\ShopFeedbackEntity
};
use backend\models\BackendUserEntity;

/* @var $this View */
/* @var $modelProfile ShopProfileEntity */
/* @var $shopFeedback ShopFeedbackEntity */
/* @var $rating array */
/* @var $user BackendUserEntity */
/* @var $shopFeedbackProvider ActiveDataProvider */
/* @var $confidentiality ShopConfidentialityEntity */
/* @var $notificationsSettings ShopNotificationsSettingsEntity */

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
                            'action'               => ['shop-profile/index'],
                            'enableAjaxValidation' => true,
                            'validateOnChange'     => true,
                            'options'              => ['enctype' => 'multipart/form-data', 'validateOnSubmit' => true],
                        ]); ?>
                        <h3 class="panel-title">Фото профиля</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                            $image = '<img alt="Фото магазина"
                                src="' . ShopProfileEntity::getCurrentImage(@Yii::$app->user->identity->getId()) .
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
                            <?= $formProfile->field($modelProfile, 'image')->widget(FileInput::className(), [
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
                        <?php if ($rating && isset($rating['average_rating'])): ?>
                            <div class="container">
                                <div class="row">
                                    <h5 class="padding-bottom-7"> Средний рейтинг <?= $rating['average_rating']; ?>
                                        <small>/ 5</small>
                                        <input type="number" class="rating" min=0 max=5 data-size="xs"
                                               value="<?= $rating['average_rating']; ?>"
                                               readonly data-show-clear="false" data-show-caption="false">
                                    </h5>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                 </div>
                 <?php if ($rating && isset($rating['average_rating'])): ?>
                 <div class="panel panel-default">
                     <div class="panel-heading">
                         <h3 class="panel-title">Рейтинг по категориям</h3>
                     </div>
                     <?php if ($rating) : ?>
                         <div class="panel-body">
                             <div class="container">
                                 <div class="row">
                                     <h5 class="padding-bottom-7">Качество товара
                                         <?= $rating['average_product_rating']; ?> <small>/ 5</small>
                                         <input type="number" class="rating" min=0 max=5 data-size="xs"
                                             value="<?= $rating['average_product_rating']; ?>"
                                             readonly data-show-clear="false" data-show-caption="false">
                                     </h5>
                                 </div>
                                 <div class="row">
                                     <h5 class="padding-bottom-7">Работа оператора
                                         <?= $rating['average_operator_rating']; ?> <small>/ 5</small>
                                         <input type="number" class="rating" min=0 max=5 data-size="xs"
                                             value="<?= $rating['average_operator_rating']; ?>"
                                             readonly data-show-clear="false" data-show-caption="false">
                                     </h5>
                                 </div>
                                 <div class="row">
                                     <h5 class="padding-bottom-7">Надежность
                                         <?= $rating['average_reliability_rating']; ?> <small>/ 5</small>
                                         <input type="number" class="rating" min=0 max=5 data-size="xs"
                                             value="<?= $rating['average_reliability_rating']; ?>"
                                             readonly data-show-clear="false" data-show-caption="false">
                                     </h5>
                                 </div>
                                 <div class="row">
                                     <h5 class="padding-bottom-7">Качество доставки
                                         <?= $rating['average_marker_rating']; ?> <small>/ 5</small>
                                         <input type="number" class="rating" min=0 max=5 data-size="xs"
                                             value="<?= $rating['average_marker_rating']; ?>"
                                             readonly data-show-clear="false" data-show-caption="false">
                                     </h5>
                                 </div>
                             </div>
                         </div>
                     <?php endif; ?>
                 </div>
                 <?php endif; ?>
            </div>
        </div>
        <?php if ($user->status === $user::STATUS_UNVERIFIED) : ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Верификация</h3>
                </div>
                <div class="panel-body">
                    <form action="index"><button  type="submit" hidden></button></form>
                    <?php $formVerify = ActiveForm::begin([
                        'action' => ['shop-profile/verify'],
                    ]); ?>
                    <?= $formVerify->field($user, 'verification_code')->textInput(['value' => '']); ?>
                    <div class="form-group">
                        <?= Html::submitButton('Активировать', ['class' => 'btn btn-success']); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <?php $formSendCode = ActiveForm::begin(['action' => ['shop-profile/send-verification-code']]); ?>
                    <?= Html::submitButton('Выслать код еще раз', ['class' => 'btn btn-primary']); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-sm-9">

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#profile">Профиль</a></li>
            <li><a data-toggle="tab" href="#reviews">Отзывы</a></li>
            <li><a data-toggle="tab" href="#settings">Смена пароля</a></li>
            <li><a data-toggle="tab" href="#confidentiality">Конфиденциальность</a></li>
            <li><a data-toggle="tab" href="#notifications-settings">Настройка оповещений</a></li>
        </ul>

        <div class="tab-content" style="background-color:#ffffff; border: 1px solid #f0f0f0; padding: 5px">
            <div id="profile" class="tab-pane fade in active">


                <?= $formProfile->field($modelProfile, 'name')->input('text'); ?>

                <?= $formProfile->field($modelProfile, 'status_text')->input('text'); ?>

                <div class="row">
                    <div class="col-xs-12">
                        <?= $formProfile->field($modelProfile, 'description')->textarea(['rows' => 6]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'work_start_time')
                            ->widget(TimePicker::className(), [
                                'size'          => 'lg',
                                'pluginOptions' => [
                                    'showMeridian' => false,
                                ]
                            ]);
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'work_end_time')
                            ->widget(TimePicker::className(), [
                                'size'          => 'lg',
                                'pluginOptions' => [
                                    'minuteStep'   => 5,
                                    'showMeridian' => false,
                                ],
                            ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <?= $formProfile->field($modelProfile, 'towns')->widget(Select2::className(), [
                                'value'         => array_keys(ArrayHelper::map(
                                                       $modelProfile->cities, 'id', 'name')),
                                'data'          => ArrayHelper::map(CityEntity::find()->all(), 'id', 'name'),
                                'maintainOrder' => true,
                                'options'       => ['placeholder' => 'Выберите город', 'multiple' => true],
                                'pluginOptions' => ['allowClear' => true]
                            ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'site_url')->input('text'); ?>
                    </div>
                    <div class="col-xs-6">
                        <?= $formProfile->field($modelProfile, 'jabber', ['template' => "
                            <i class='fa fa-lightbulb-o'>&nbsp</i>{label}\n{input}\n{hint}\n{error}"])
                            ->input('text');
                        ?>
                    </div>
                </div>
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

                <?php if ($user->status === $user::STATUS_VERIFIED) : ?>
                <div class="form-group">
                    <?= Html::submitButton('Применить', ['class' => 'btn btn-success']); ?>
                </div>
                <?php elseif ($user->status === $user::STATUS_UNVERIFIED) : ?>
                <div>
                    <h4 style="color: red">Для редактирования своего профиля пройдите верификацию аккаунта</h4>
                </div>
                <?php endif; ?>

                <?php ActiveForm::end(); ?>
            </div>

            <div id="reviews" class="tab-pane fade">
                <?php Pjax::begin(); ?>

                <?= GridView::widget([
                    'dataProvider' => $shopFeedbackProvider,
                    'filterModel'  => $shopFeedback,
                    'columns'      => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'created_by',
                            'format'    => 'html',
                            'filter'    => Html::activeDropDownList(
                                $shopFeedback,
                                'created_by',
                                ArrayHelper::map(BackendUserEntity::find()->where([
                                        'status' => BackendUserEntity::STATUS_VERIFIED
                                ])->asArray()->all(), 'id', 'email'),
                                ['class' => 'form-control', 'value' => Yii::$app->request
                                    ->get('ShopFeedbackEntity')['created_by'], 'prompt' => '']
                            ),
                            'value' => function($model) {
                                $roles = Yii::$app->authManager->getRolesByUser($model->created_by);
                                if (isset($roles['shop'])) {
                                    $profile = ShopProfileEntity::find()
                                        ->select('user_id, name as nickname, image as avatar')
                                        ->where(['user_id' => $model->created_by])->asArray()->one();
                                    $profile['avatar'] = ShopProfileEntity::getCurrentImage($profile['user_id']);
                                } else {
                                    $profile = UserProfileEntity::find()->where(['user_id' => $model->created_by])
                                        ->asArray()->one();
                                    $profile['avatar'] = UserProfileEntity::getCurrentImage($profile['user_id']);
                                }
                                return "<div class='col-sm-3'>
                                    <img src='" . $profile['avatar'] . "' class='img-circle img-responsive' width='50%'>
                                    <div class='review-block-name'><a href='#'>" . $profile['nickname'] . "</a></div>
                                    <div class='review-block-date'>" . date('Y-m-d H:i:s', $model->created_at)
                                        . "<br/>1 day ago
                                    </div>
                                </div>";
                            }
                        ],
                        [
                            'attribute' => 'average_rating',
                            'label'     => 'Отзыв по категориям',
                            'format'    => 'raw',
                            'value' => function($model) {
                                return   (
                                    ('Качество товара'. "<br/>" . StarRating::widget([
                                        'model' => $model,
                                        'name' => 'average_rating',
                                        'value' => $model->product_rating,
                                        'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                                    )
                                    . ('Работа оператора'. "<br/>" . StarRating::widget([
                                            'model' => $model,
                                            'name' => 'average_rating',
                                            'value' => $model->operator_rating,
                                            'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                                    )
                                    . ('Надежность'. "<br/>" . StarRating::widget([
                                            'model' => $model,
                                            'name' => 'average_rating',
                                            'value' => $model->reliability_rating,
                                            'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                                    )
                                    . ('Качество доставки'. "<br/>" . StarRating::widget([
                                            'model' => $model,
                                            'name' => 'average_rating',
                                            'value' => $model->marker_rating,
                                            'pluginOptions' => ['displayOnly' => true, 'size' => 'xs']])
                                    )
                                );
                            }
                        ]
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>

            <div id="settings" class="tab-pane fade">
                <?php $form = ActiveForm::begin([
                    'action' => ['shop-profile/update-password'],
                    'method' => 'post',
                ]); ?>
                <?= $form->field($user, 'currentPassword')->passwordInput([
                    'value'       => '',
                    'placeholder' => 'Введите текущий пароль..',
                ]); ?>

                <?= $form->field($user, 'password_hash')->passwordInput([
                    'value'       => '',
                    'placeholder' => 'Введите новый пароль..',
                ]); ?>
                <?= $form->field($user, 'confirm')->passwordInput([
                    'value'       => '',
                    'placeholder' => 'Подтвердите пароль..',
                ]); ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                <?php ActiveForm::end(); ?>
            </div>

            <div id="confidentiality" class="tab-pane fade">
                <?php $form = ActiveForm::begin([
                    'action' => ['shop-profile/update-confidentiality'],
                    'method' => 'post',
                ]); ?>
                <?php
                    $confidentiality = ShopConfidentialityEntity::findOne([
                        'user_id' => @Yii::$app->user->identity->getId()
                    ]);
                ?>
                <?= $form->field($confidentiality, 'show_status_online')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($confidentiality, 'view_page_access')->dropDownList([
                    'ALL_USERS'        => 'Все пользователи',
                    'REGISTERED_USERS' => 'Все зарегестрированные пользователи',
                    'NOBODY'           => 'Никто'
                ]); ?>
                <?= $form->field($confidentiality, 'send_messages_access')->dropDownList([
                    'ALL_USERS'        => 'Все пользователи',
                    'REGISTERED_USERS' => 'Все зарегестрированные пользователи',
                    'NOBODY'           => 'Никто'
                ]); ?>
                <?= $form->field($confidentiality, 'frequency_history_cleaning')->dropDownList([
                    'ONE_MINUTE'   => 'Каждую минуту',
                    'FIVE_MINUTES' => 'Каждые 5 минут',
                    'ONE_HOUR'     => 'Каждый час',
                    'THREE_HOURS'  => 'Каждые 3 часа',
                    'FIVE_HOURS'   => 'Каждые 5 часов',
                    'TWELVE_HOURS' => 'Каждые 12 часов',
                    'ONE_DAY'      => 'Каждый день',
                    'SEVEN_DAYS'   => 'Каждую неделю',
                    'NEVER'        => 'Никогда'

                ]); ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                <?php ActiveForm::end(); ?>
            </div>

            <div id="notifications-settings" class="tab-pane fade">
                <?php
                    $form = ActiveForm::begin([
                        'action' => ['shop-profile/update-notifications-settings'],
                        'method' => 'post',
                    ]);
                ?>
                <?php
                    $notificationsSettings = ShopNotificationsSettingsEntity::findOne([
                       'user_id' => @Yii::$app->user->identity->getId()
                    ]);
                ?>
                <div class="big-block">
                <div style=" width:100%; height:1px; clear:both;">.</div>
                <div class="small-block" style="float:left;">
                <?= $form->field($notificationsSettings, 'new_personal_message')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($notificationsSettings, 'new_review')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($notificationsSettings, 'new_reply_comment')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($notificationsSettings, 'new_product_report')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($notificationsSettings, 'new_theme_comment')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                </div>
                <div class="small-block" style="float:left; margin-left:50px">
                <?= $form->field($notificationsSettings, 'theme_was_verified')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($notificationsSettings, 'new_like')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($notificationsSettings, 'messages_to_email')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                <?= $form->field($notificationsSettings, 'site_dispatch')
                    ->widget(SwitchInput::className(), [
                        'type' => SwitchInput::CHECKBOX
                    ]); ?> <br />
                </div>
                <div style=" width:100%; height:1px; clear:both;"></div>
                </div>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div><!--/col-->
    </div>
</div>