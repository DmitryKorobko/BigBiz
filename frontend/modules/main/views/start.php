<?php
use frontend\assets\{
    AppAsset, StartAsset
};

AppAsset::register($this);
StartAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'Авторизация/Регистрация';
?>

<section class="forms">
    <div class="container box">
        <a href="/main/index" class="logo z-depth-3"></a>
            <div class="row">
                <div class="col s12 m10 offset-m1 l6 offset-l3 z-depth-5 forms-box">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s6"><a href="#login">Авторизация</a></li>
                            <li class="tab col s6"><a href="#registration">Регистрация</a></li>
                        </ul>
                    </div>
                    <div id="login" class="col s12 ">
                        <form action="" class="col s12 loginForm">
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="fa fa-envelope fa-fw prefix" aria-hidden="true"></i>
                                    <input id="log-email" type="email" class="validate">
                                    <label for="log-email">Email</label>
                                </div>
                                <div class="input-field col s12">
                                    <i class="fa fa-lock fa-fw prefix" aria-hidden="true"></i>
                                    <input id="log-password" type="password" class="validate">
                                    <label for="log-password">Пароль</label>
                                </div>
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light green log-btn" type="submit"
                                            name="action">Войти
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="registration" class="col s12">
                        <form action="" class="col s12 regForm">
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="fa fa-envelope fa-fw prefix" aria-hidden="true"></i>
                                    <input id="reg-email" type="email" class="validate">
                                    <label for="reg-email">Email</label>
                                </div>
                                <div class="input-field col s12">
                                    <i class="fa fa-lock fa-fw prefix" aria-hidden="true"></i>
                                    <input id="reg-password" type="password" class="validate">
                                    <label for="reg-password">Пароль</label>
                                </div>
                                <div class="input-field col s12">
                                    <i class="fa fa-lock fa-fw prefix" aria-hidden="true"></i>
                                    <input id="re-reg-password" type="password" class="validate">
                                    <label for="re-reg-password">Повторите пароль</label>
                                </div>
                                <button class="btn waves-effect waves-light green reg-btn" type="submit"
                                        name="action">Зарегистрироваться
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>