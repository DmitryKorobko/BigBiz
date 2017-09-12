<?php
use common\models\{
    shop_profile\ShopProfileEntity, admin_contact\AdminContactEntity
};
use backend\assets\AppAsset;
use yii\helpers\{
    Html, Url
};
use \davidhirtz\yii2\timeago\Timeago;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>

<header class="header">

    <?= Html::a('Admin Panel', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-right">

            <ul class="nav navbar-nav">
                <?php if ((Yii::$app->user->can('shop'))
                || (Yii::$app->user->can('admin'))) : ?>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope"></i>
                        <span class="label label-success"><?= $this->params['newMessages'] ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">У вас <?= $this->params['newMessages'] ?> новых сообщений</li>
                        <li>
                            <ul class="menu">
                                <?php foreach ($this->params['messages'] as $messageModel) { ?>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="<?= $messageModel['creator']['avatar'] ?>"
                                                 class="img-circle" alt="User Image"/>
                                        </div>
                                        <h4>
                                            <?= $messageModel['creator']['name'] ?>
                                            <small>
                                                <i class="fa fa-clock-o"></i>
                                                <?= Timeago::tag($messageModel['created_at']) ?>
                                            </small>
                                        </h4>
                                        <p><?= $messageModel['text'] ?></p>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">Просмотреть все сообщения</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if (Yii::$app->user->can('shop')) : ?>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span class="label label-warning">
                            <?= $this->params['newAnswers'] ?>
                        <span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">У вас <?= $this->params['newAnswers'] ?> новых ответов</li>
                        <li>
                            <ul class="menu">
                                <?php foreach ($this->params['answers'] as $answerModel) { ?>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="<?= $answerModel['creator']['avatar'] ?>"
                                                 class="img-circle" alt="User Image"/>
                                        </div>
                                        <h4>
                                            <?= $answerModel['creator']['name'] ?>
                                            <small>
                                                <i class="fa fa-clock-o"></i>
                                                <?= Timeago::tag($answerModel['created_at']) ?>
                                            </small>
                                        </h4>
                                        <p><?= $answerModel['text'] ?></p>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">Просмотреть все ответы</a></li>
                    </ul>
                </li>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-star"></i>
                        <span class="label label-warning"><?= $this->params['newReviews'] ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">У вас <?= $this->params['newReviews'] ?> новых отзывов</li>
                        <li>
                            <ul class="menu">
                                <?php foreach ($this->params['reviews'] as $reviewModel) { ?>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="<?= $reviewModel['creator']['avatar'] ?>"
                                                 class="img-circle" alt="User Image"/>
                                        </div>
                                        <h4>
                                            <?= $reviewModel['creator']['name'] ?>
                                            <small>
                                                <i class="fa fa-clock-o"></i>
                                                <?= Timeago::tag($reviewModel['created_at']) ?>
                                            </small>
                                        </h4>
                                        <p><?= $reviewModel['average_rating'] ?></p>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="/admin/shop/profile/shop-profile">Просмотреть все отзывы</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if (Yii::$app->user->can('admin')) : ?>
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-warning"></i>
                            <span class="label label-warning"><?= $this->params['newFeedbacks'] ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">У вас <?= $this->params['newFeedbacks'] ?> новых пожеланий и сообщений
                                об ошибках
                            </li>
                            <li>
                                <ul class="menu"><?php foreach ($this->params['feedbacks'] as $feedbackModel) { ?>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="<?= $feedbackModel['creator']['avatar'] ?>"
                                                         class="img-circle" alt="User Image"/>
                                                </div>
                                                <h4>
                                                    <?= $feedbackModel['creator']['name'] ?>
                                                    <small>
                                                        <i class="fa fa-clock-o"></i>
                                                        <?= Timeago::tag($feedbackModel['created_at']) ?>
                                                    </small>
                                                </h4>
                                                <p style="text-decoration-line: underline">
                                                    <?= $feedbackModel['name'] ?>
                                                </p>
                                                <p><?= $feedbackModel['message'] ?></p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li class="footer"><a href="/admin/manage/profile/manage-profile">Просмотреть все</a></li>
                        </ul>
                    </li>
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-tags"></i>
                            <span class="label label-warning"> <?= $this->params['newThemesForAdminCount'] ?> </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">У вас <?= $this->params['newThemesForAdminCount'] ?>
                                новых тем в категориях Арбитраж и Частные объявления</li>
                            <li>
                                <ul class="menu">
                                    <?php foreach ($this->params['newThemesForAdmin'] as $newThemesForAdminModel) { ?>
                                    <li>
                                        <a href="#">
                                            <div class="pull-left">
                                                <img src="<?= $newThemesForAdminModel['user_avatar'] ?>" class="img-circle"
                                                     alt="User Image"/>
                                            </div>
                                            <h4>
                                                <?= $newThemesForAdminModel['user_name'] ?>
                                                <small>
                                                    <i class="fa fa-clock-o"></i>
                                                    <?= Timeago::tag($newThemesForAdminModel['date_of_publication']) ?>
                                                </small>
                                            </h4>
                                            <p> <?= $newThemesForAdminModel['name'] ?></p>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li class="footer"><a href="/admin/manage/profile/manage-profile">Просмотреть все темы</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (Yii::$app->user->can('moder')) : ?>
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-comments"></i>
                            <span class="label label-warning"><?= $this->params['newAnswers'] ?><span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?= $this->params['newAnswers'] ?> новых ответов на комментарии</li>
                            <li>
                                <ul class="menu">
                                    <?php foreach ($this->params['answers'] as $answerModel) { ?>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="<?= $answerModel['creator']['avatar'] ?>"
                                                         class="img-circle" alt="User Image"/>
                                                </div>
                                                <h4>
                                                    <?= $answerModel['creator']['name'] ?>
                                                    <small>
                                                        <i class="fa fa-clock-o"></i>
                                                        <?= Timeago::tag($answerModel['created_at']) ?>
                                                    </small>
                                                </h4>
                                                <p><?= $answerModel['text'] ?></p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">Просмотреть все ответы</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (Yii::$app->user->isGuest) : ?>
                    <li class="footer">
                        <?= Html::a('Login', ['/']) ?>
                    </li>
                <?php else : ?>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <?php if (Yii::$app->user->can('shop')) : ?>
                                <span><?= ShopProfileEntity::getCurrentName(); ?> <i class="caret"></i></span>
                            <?php else: ?>
                                <span><?= @Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header bg-light-blue">
                                <?php if (Yii::$app->user->can('shop')) : ?>
                                    <img src="<?= ShopProfileEntity::getCurrentImage(
                                            @Yii::$app->user->identity->getId()
                                    ); ?>"
                                         class="img-circle" alt="User Image"/>
                                <?php else: ?>
                                    <img  src="<?= AdminContactEntity::getCurrentImage(@Yii::$app->user->identity->getId()); ?>"
                                          class="img-circle" alt="User Image"/>
                                <?php endif; ?>
                                <p>
                                    <?php if (Yii::$app->user->can('shop')) : ?>
                                        <span><?= ShopProfileEntity::getCurrentName(); ?></span>
                                    <?php else: ?>
                                        <span><?= @Yii::$app->user->identity->username ?></span>
                                    <?php endif; ?>
                                    <small>На сайте с
                                        <?= date('F j, Y', $this->params['user_created_at']) ?>
                                    </small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <?php if (Yii::$app->user->can('shop')) : ?>
                                    <div class="pull-left">
                                        <a href="<?= Url::to(['/shop/profile/shop-profile']) ?>"
                                           class="btn btn-default btn-flat">Мой профиль</a>
                                        <a href="<?= Url::to(['/../main/shop-profile?id=' .
                                            @Yii::$app->user->identity->getId()]) ?>"
                                           target="_blank"
                                           class="btn btn-default btn-flat">Форум</a>
                                    </div>
                                <?php elseif (Yii::$app->user->can('admin')): ?>
                                    <div class="pull-left">
                                        <a href="<?= Url::to(['/manage/profile/manage-profile']) ?>"
                                           class="btn btn-default btn-flat">Мой профиль</a>
                                    </div>
                                <?php endif; ?>
                                <div class="pull-right">
                                    <?= Html::a(
                                        'Выход',
                                        ['/authorization/authorization/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
