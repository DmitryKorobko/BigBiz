<?php
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte';
    /* @var $profile \common\models\user_profile\UserProfileEntity */
?>
<div class="col-sm-3">
    <div class="row">
        <div class="col-xs-7 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Фото профиля</h3>
                </div>
                <div class="panel-body">
                    <img style="margin: auto" alt="Фото пользователя"
                         <?php if ($profile->avatar) { ?>
                             src="<?= $profile->avatar ?>"
                         <?php } else { ?>
                             src="<?= $directoryAsset ?>/img/avatar5.png"
                         <?php } ?>
                         class="img-circle img-responsive">
                </div>
            </div>
        </div>
        <div class="col-xs-5 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Основная информация</h3>
                </div>
                <div class="panel-body">
                    <ul class="profile-details">
                        <li>
                            <div> Имя пользователя: <b><?php echo $profile->nickname; ?></b></div>
                        </li>
                        <li>
                            <div>
                                E-mail:
                                <td>
                                    <a href="mailto:<?= @Yii::$app->user->identity
                                        ->findIdentity($profile->user_id)->username ?>">
                                        <?= @Yii::$app->user->identity->findIdentity($profile->user_id)->username ?>
                                    </a>
                                </td>
                            </div>

                        </li>
                        <li>
                            <div>
                                На форуме с: <b><?= date('F j, Y', @Yii::$app->user->identity
                                        ->findIdentity($profile->user_id)->created_at); ?></b>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>