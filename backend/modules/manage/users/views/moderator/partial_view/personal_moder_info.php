<?php
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte';
    /* @var $modelUser \common\models\user\UserEntity */
    /* @var $rating  \common\models\shop_feedback\ShopFeedbackEntity */
?>
<div class="col-sm-3">
    <div class="row">
        <div class="col-xs-7 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Фото модератора</h3>
                </div>
                <div class="panel-body">
                    <img alt="Фото модератора" style="margin: auto"
                         src="<?= $directoryAsset ?>/img/avatar5.png" class="img-circle img-responsive"/>
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
                            <div> Звание: <b>Модератор</b></div>
                        </li>
                        <li>
                            <div>
                                E-mail:
                                <td>
                                    <a href="mailto:<?= @Yii::$app->user->identity
                                        ->findIdentity($modelUser->id)->username ?>">
                                        <?= @Yii::$app->user->identity->findIdentity($modelUser->id)->username ?>
                                    </a>
                                </td>
                            </div>

                        </li>
                        <li>
                            <div>
                                На форуме с: <b><?= date('F j, Y', @Yii::$app->user->identity
                                        ->findIdentity($modelUser->id)->created_at); ?></b>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>