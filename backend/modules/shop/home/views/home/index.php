<?php

/* @var $this yii\web\View */
/** @var $contact \common\models\admin_contact\AdminContactEntity; */

$this->title = 'Admin Panel';
// todo все переменные должны подсвечиваться
?>
<hr/>
<div class="site-index">
    <h4>Связаться с администрацией</h4>
    <div class="row text-center">
        <div class="col-sm-6 col-xs-6 first-box">
            <h1><i class="fa fa-skype fa-5"></i></h1>
            <h3>Skype</h3>
            <p> <?= $contact->skype ?> </p><br>
        </div>
        <div class="col-sm-6 col-xs-6 third-box">
            <h1><i class="fa fa-vimeo-square fa-5"></i></h1>
            <h3>Viber</h3>
            <p><?= $contact->viber ?></p><br>
        </div>
        <div class="col-sm-6 col-xs-6 second-box">
            <h1><i class="fa fa-telegram fa-5"></i></h1>
            <h3>Telegram</h3>
            <p> <?= $contact->telegram ?> </p><br>
        </div>
        <div class="col-sm-6 col-xs-6 fourth-box">
            <h1><i class="fa fa-shield fa-5"></i></h1>
            <h3>Vipole</h3>
            <p><?= $contact->vipole ?></p><br>
        </div>
        <div class="col-sm-6 col-xs-6 fifth-box">
            <h1><i class="fa fa-fire fa-5"></i></h1>
            <h3>Jabber</h3>
            <p> <?= $contact->jabber ?> </p><br>
        </div>
    </div>
    <hr/>
    <h4>Срок действия аккаунта и отзывы о магазине</h4>
    <div class="row text-center">
        <div class="col-sm-6 col-xs-6 fourth-box">
            <h1><i class="fa fa-clock-o fa-5"></i></h1>
            <h3><?= $account_days_left ?></h3>
            <?php if (($not_active)) {
                Yii::$app->getSession()->setFlash('error', "для того, чтобы активировать аккаунт,
                свяжитесь с администрацией");
            } ?>
            <p><br></p><br>
        </div>
        <div class="col-sm-6 col-xs-6 second-box">
            <h1><i class="fa fa-star fa-5"></i></h1>
            <h3>Отзывов:<?php echo ' ' . $count_of_shop_reviews ?></h3>
            <p> <a style="color: #0a0a0a" href="<?= \yii\helpers\Url::to(['/shop/profile/shop-profile']); ?>" class="small-box-footer">
                    Перейти к профилю <i class="fa fa-arrow-circle-right"></i>
                </a>
            </p><br>
        </div>
    </div>
    <hr/>
    <h4>Статистика магазина</h4>
    <section class="content">
        <div class="row">
            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h1><i class="fa fa-shopping-cart fa-5" style="color: #0a0a0a; margin-left: 47%;"></i></h1>
                        <h4 style="text-align: center">Кол-во товаров:<?= ' ' . $count_products; ?></h4>
                    </div>
                    <a href="<?= \yii\helpers\Url::to(['/shop/control/product']); ?>" class="small-box-footer">
                        Подробнее <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h1><i class="fa fa-bullhorn fa-5" style="color: #0a0a0a; margin-left: 47%;"></i></h1>
                        <h4 style="text-align: center">Кол-во тем:<?= ' ' . $count_themes; ?></h4>
                    </div>
                    <a href="<?= \yii\helpers\Url::to(['/shop/control/theme']); ?>" class="small-box-footer">
                        Подробнее <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

<!--            <div class="col-lg-6 col-xs-6">-->
<!--                <!-- small box -->
<!--                <div class="box box-primary collapsed-box">-->
<!--                    <div class="box-header with-border">-->
<!--                        <h3 class="box-title">Самые популярные товары</h3>-->
<!---->
<!--                        <div class="box-tools pull-right">-->
<!--                            <button type="button" class="btn btn-box-tool" data-widget="collapse">-->
<!--                                <i class="fa fa-plus"></i>-->
<!--                            </button>-->
<!--                            <button type="button" class="btn btn-box-tool" data-widget="remove">-->
<!--                                <i class="fa fa-times"></i>-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <!-- /.box-header -->
<!--                    <div class="box-body" style="display: block; margin-left: -25px;">-->
<!--                        <ul class="products-list product-list-in-box">-->
<!--                            --><?php //foreach ($most_popular_products as $product) { ?>
<!--                            <li class="item" style="list-style-type: none;">-->
<!--                                <hr>-->
<!--                                <div class="product-img product-info">-->
<!--                                    <img src="--><?php //echo $product['image'] ?><!--" width="50" height="50"-->
<!--                                         alt="Изображеие товара">-->
<!--                                    <a style="margin-left: 15px" href="javascript:void(0)" class="product-title">-->
<!--                                        --><?php //echo $product['name'] ?>
<!--                                    </a>-->
<!--                                    <span style="margin-top: 15px" class="label label-warning pull-right">-->
<!--                                           --><?php //echo $product['price'] . ' UAH' ?>
<!--                                       </span>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                            --><?php //} ?>
<!--                            <!-- /.item -->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <!-- /.box-body -->
<!--                    <div class="box-footer text-center" style="display: none;">-->
<!--                        <a href="--><?//= \yii\helpers\Url::to(['/shop/control/product']); ?><!--" class="uppercase">-->
<!--                            Посмотреть все товары-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <!-- /.box-footer -->
<!--                </div>-->
<!--            </div>-->


        </div>
        <div class="col-md-4">
        </div>
    </section>
    <hr/>
</div>
