<?php
use yii\helpers\Html;
use frontend\assets\{
    AppAsset, MainPageAsset
};

/* @var $this \yii\web\View */
/* @var $content string */

if (Yii::$app->controller->id === 'start') :
    echo $this->render('wrapper', ['content' => $content]);
else :
    AppAsset::register($this);
    MainPageAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte';
?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <?= Html::csrfMetaTags() ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= Html::encode($this->title) ?></title>
        <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/favicon/manifest.json">
        <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="theme-color" content="#ffffff">

        <?php $this->head() ?>
    </head>

    <body>
    <div id="tt-preloader">
        <div class="loader-inner ball-grid-pulse">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div id="top"></div>
    <?php $this->beginBody() ?>

    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

        <!-- Scroll-up -->
        <div class="scroll-up">
            <a href="#top"><i class="material-icons">expand_less</i></a>
        </div>

        <!--Modal-->
        <div id="login-modal" class="modal z-depth-4">
            <div class="input-field col s12 center modal-title">
                <img class="responsive-img logo-white" src="/img/logo-small.png" alt="logo">
                <a class="modal-close"><i class="material-icons white-text">close</i></a>
            </div>
            <div class="modal-content">
                <div class="row no-margin-bot">
                    <div class="col s12">
                        <div class="center-align">
                            <h3 class="border-line">Авторизация пользователя</h3>
                        </div>
                    </div>
                    <form class="login-form col s12">
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-envelope prefix" aria-hidden="true"></i>
                                <input id="email" type="email" class="validate">
                                <label for="email">E-mail</label>
                            </div>
                        </div>
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-lock prefix" aria-hidden="true"></i>
                                <input id="password" type="password">
                                <label for="password">Пароль</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 center-align">
                                <a class="forgot-pass" data-target="forgot-modal">Забыли пароль?</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 center-align no-margin-bot">
                                <a href=""
                                   class="waves-effect waves-light btn transparent-color enter-btn white-text">
                                    Войти
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="forgot-modal" class="modal z-depth-4">
            <div class="input-field col s12 center modal-title">
                <img class="responsive-img logo-white" src="/img/logo-small.png" alt="logo">
                <a class="modal-close"><i class="material-icons white-text">close</i></a>
            </div>
            <div class="modal-content">
                <div class="row no-margin-bot">
                    <div class="col s12">
                        <div class="center-align">
                            <h3 class="border-line">Восстановление доступа</h3>
                        </div>
                    </div>
                    <form class="send-code-form col s12">
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-envelope prefix" aria-hidden="true"></i>
                                <input id="forgot-email" type="email" class="validate">
                                <label for="forgot-email">E-mail</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 center-align no-margin-bot">
                                <a href="" class="waves-effect waves-light btn transparent-color code-btn white-text">
                                    Выслать код
                                </a>
                            </div>
                        </div>
                    </form>
                    <form class="col s12">
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-envelope prefix" aria-hidden="true"></i>
                                <input disabled id="code" type="password">
                                <label for="code">Код из письма</label>
                            </div>
                        </div>
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-lock prefix" aria-hidden="true"></i>
                                <input disabled id="new-password" type="password">
                                <label for="new-password">Новый пароль</label>
                            </div>
                        </div>
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-lock prefix" aria-hidden="true"></i>
                                <input disabled id="new-password-repeat" type="password">
                                <label for="new-password-repeat">Подтвердите пароль</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 center-align no-margin-bot">
                                <a href=""
                                   class="waves-effect waves-light btn transparent-color save-btn white-text disabled">
                                    Сохранить
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="reg-modal" class="modal registration z-depth-4">
            <div class="input-field col s12 center modal-title">
                <img class="responsive-img logo-white" src="/img/logo-small.png" alt="logo">
                <a class="modal-close"><i class="material-icons white-text">close</i></a>
            </div>
            <div class="modal-content">
                <div class="row no-margin-bot">
                    <div class="col s12">
                        <div class="center-align">
                            <h3 class="border-line">Регистрация</h3>
                        </div>
                    </div>
                    <form class="reg-form col s12">
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-envelope prefix" aria-hidden="true"></i>
                                <input id="reg-email" type="email" class="validate">
                                <label for="reg-email">Введите E-mail</label>
                            </div>
                        </div>
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-user  prefix" aria-hidden="true"></i>
                                <input id="username" type="text">
                                <label for="username">Username</label>
                            </div>
                        </div>
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-lock prefix" aria-hidden="true"></i>
                                <input id="reg-password" type="password">
                                <label for="reg-password">Введите пароль</label>
                            </div>
                        </div>
                        <div class="row no-margin-bot">
                            <div class="input-field col s12 m10 offset-m1">
                                <i class="fa fa-lock prefix" aria-hidden="true"></i>
                                <input id="reg-password-repeat" type="password">
                                <label for="reg-password-repeat">Введите пароль еще раз</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m10 offset-m1">
                                <input type="checkbox" id="regulations">
                                <label for="regulations">Я согласен с
                                    <a class="green-text" href="#">условиями и правилами</a>
                                    форума
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 center-align no-margin-bot">
                                <a href=""
                                   class="waves-effect waves-light btn transparent-color registration-btn white-text">
                                    Зарегистрироваться
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?= $this->render(
            'footer.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php endif; ?>
