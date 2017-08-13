<?php

/* @var $modelTheme \common\models\theme\ThemeEntity */
/* @var $comment  \common\models\comment\CommentEntity */
/* @var $dataProviderTheme \yii\data\ActiveDataProvider */
/* @var $dataProviderComments \yii\data\ActiveDataProvider */
/* @var $modelUser \common\models\user\UserEntity */

$this->params['breadcrumbs'][] = ['label' => 'Модераторы сайта', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Активность модератора';
$user = \common\models\user\UserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);

?>

<div class="personal-info-update">
    <?php if ($modelUser->email) : ?>
        <?= $this->render('partial_view/personal_moder_info', ['modelUser' => $modelUser], true); ?>
        <div class="col-sm-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#theme">Темы</a></li>
                <li><a data-toggle="tab" href="#comments">Комментарии</a></li>
            </ul>
            <div class="tab-content"  style="background-color:#ffffff; border: 1px solid #f0f0f0; padding: 5px">
                <?= $this->render('partial_view/list_moder_themes', ['dataProviderTheme' => $dataProviderTheme,
                    'modelTheme' => $modelTheme], true); ?>
                <?= $this->render('partial_view/moder_comments', ['dataProviderComments' => $dataProviderComments,
                    'comment' => $comment, 'modelUser' => $modelUser], true); ?>
            </div>
        </div>
    <?php endif; ?>
</div>