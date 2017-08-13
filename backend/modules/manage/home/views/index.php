<?php

/* @var $this yii\web\View */

$this->title = 'Admin Panel';
?>
<div class="site-index">
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>
                            <?= $count_of_all_users ?>
                        </h3>
                        <p>
                            Пользователи
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-people"></i>
                    </div>
                    <br>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>
                            <?= $count_of_new_users ?>
                        </h3>
                        <p>
                            Новые пользователи
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-personadd"></i>
                    </div>
                    <br>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            <?= $count_of_all_shops ?>
                        </h3>
                        <p>
                            Магазины
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-cart"></i>
                    </div>
                    <br>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                            <?= $count_of_new_shops ?>
                        </h3>
                        <p>
                            Новые магазины
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-cart-outline"></i>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3>
                            <?= $count_of_all_products ?>
                        </h3>
                        <p>
                            Товары
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <br>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>
                            <?= $count_of_all_themes ?>
                        </h3>
                        <p>
                            Темы
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-speakerphone"></i>
                    </div>
                    <br>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>
                            <?= $count_of_all_comments ?>
                        </h3>
                        <p>
                            Комментарии
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-chatbubbles"></i>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </section>
</div>
