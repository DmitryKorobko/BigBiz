<?php
use backend\assets\ProductAsset;

ProductAsset::register($this);

$this->title = "Продукт";
?>
<main>
    <!-- Product -->
    <section class="product">
        <div class="container">
            <div class="breadcrumb-box">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <h3>
                            <a href="../../../" ><i class="fa fa-home" aria-hidden="true"></i></a>
                            <a href="view?id=<?= $product->user_id ?>">/ Профиль магазина</a>
                            <a href="view-product?id=<?= $product->id ?>">/ Продукт</a>
                        </h3>
                    </div>
                </div class="nav-wrapper">
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="product-content">
                        <span class="product-name"><?= $product->name ?></span>
                        <div class="product-info row no-margin-bot">
                            <div class="col s12 m5">
                                <div class="product-image">
                                    <div class="zoom-small-image">
                                        <a href='<?= $product->image ?>' class = 'cloud-zoom' rel="position: 'inside',
                                            showTitle: false, adjustX:0, adjustY:0">
                                            <img  style="max-width: 290px; max-height: 400px"
                                                  src="<?= $product->image ?>" title="<?= $product->user_id ?>"
                                                  alt='img'/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m7">
                                <div class="product-price">
                                    <span class="price-title">Цена:</span>
                                    <div class="flex-box">
                                        <ul class="price-list">
                                            <?php foreach ($prices as $price) { ?>
                                                <li class="price-box">
                                                    <i class="fa fa-check green-text" aria-hidden="true"></i>
                                                    <b><?= $price['count'] ?>&nbsp;шт. - </b>
                                                    <span class="price green-text" style="font-size: 30px">
                                                        <?= $price['price'] ?>
                                                </span>&nbsp;
                                                    <i class="fa" aria-hidden="true">
                                                        <h3>UAH</h3>
                                                    </i>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="flex-box">
                                        <div>
                                            <?= ($product['availability'])
                                                ? '<span class="border on">в наличии</span>'
                                                : '<span class="border off red-bg">нет в наличии</span>'
                                            ?>
                                        </div>
                                        <ul class="city-list">
                                            <?php foreach ($cities as $city) { ?>
                                                <li><a class="border on" title="в наличии"><?= $city['name'] ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--start tabs-->
                        <div class="row">
                            <div class="col s12 no-padding">
                                <ul class="tabs">
                                    <li class="tab col s6 m4 l3">
                                        <a href="#description">
                                            <i class="fa fa-list-alt fa-fw" aria-hidden="true"></i>
                                            Описание
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div id="description" class="col s12 product-description">
                                <p><?= $product->description ?></p>
                            </div>
                        </div>
                        <!--end tabs-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>