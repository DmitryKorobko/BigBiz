<?php

/* @var $this yii\web\View */

$this->title = 'BigBiz';
?>
<main>
    <!-- Slider Section -->
    <section class="top-slider">
        <div class="row no-margin-bot rel">
            <div class="col s12 no-padding">
                <div id="banner-slider" class="owl-carousel">
                    <?php  foreach ($WebSiteBanners as $banner) { ?>
                    <div class="slider-item white">
                        <img height="325px" src="<?= $banner['image'] ?>" alt="banner">
                        <ul class="banner-info">
                            <li class="z-depth-3 hide-on-small-only">
                                <a href="#" class="shop-logo"><img src="<?= $banner['shop_image'] ?>" alt="logo"></a>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col s12">
                <ul class="slider-nav">
                    <li class="sliderPrevBtn">
                        <i class="large material-icons">chevron_left</i>
                    </li>
                    <li class="sliderNextBtn">
                        <i class="large material-icons">chevron_right</i>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Top Block Section -->
    <?php if (!empty($topCategory)) { ?>
    <section class="top-block section margin20">
        <div class="container">
            <div class="center-align">
                <h2 class="border-line green-text uppercase"><?= $topCategory['name'] ?></h2>
            </div>
            <div class="row">
                <div class="col s12">
                    <div class="collection charter-list">

                        <?php foreach ($topCategory['child_categories'] as $child_category) { ?>
                        <a href="/main/category?id=<?= $child_category['id'] ?>" class="collection-item avatar charter-item hoverable">
                            <img class="chapter-icons" src="/img/ico/themes-icon.svg" alt="icons">
                            <span class="title charter-title"><?= $child_category['name'] ?></span>
                            <p><?= $child_category['description'] ?></p>
                            <p>
                                Темы:
                                <span class="themes black-text"><?= $child_category['count_of_themes'] ?></span>
                                Cообщения:
                                <span class="messages black-text"><?= $child_category['count_of_comments'] ?></span>
                            </p>
                        </a>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

    <!-- Top Shop Section -->
    <section class="top-shop section">
        <div id="shops-container" class="container">
            <div class="center-align">
                <h2 class="border-line green-text uppercase">Магазины</h2>
            </div>
            <ul class="tabs change-view no-margin-bot hide-on-small-only">
                <li class="tab">
                    <a href="#grid" class="grid-view">
                        <i class="fa fa-th-large fa-2x fa-fw" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="tab">
                    <a href="#list" class="list-view">
                        <i class="fa fa-th-list fa-2x fa-fw" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
            <div id="grid" class="grid">
                <div class="grid-sizer"></div>
                <div class="gutter-sizer"></div>

                <?php foreach ($shops as $shop) { ?>
                <a href="/main/shop-profile?id=<?= $shop['user_id'] ?>"
                   class="collection-item avatar charter-item hoverable">
                <div class="grid-item lonely-shop" data-shop-number="<?= $shop['shopNumber'] ?>">
                    <div class="card medium hoverable">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img height="140px" class="activator" src="<?= $shop['image'] ?>" alt="Shop image">
                        </div>
                        <div class="card-content">
                            <span class="card-title"><?= $shop['name'] ?></span>
                            <span class="shop-text-status center"><?= $shop['status_text'] ?></span>
                            <ul class="rating-list-big flex-box">
                                <?php for ($i = 0; $i < round($shop['rating']['average_rating']); $i++) { ?>
                                    <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                <?php } if ((5 - round($shop['rating']['average_rating'])) > 0) {
                                    for ($i = 0; $i < (5 - round($shop['rating']['average_rating'])); $i++) { ?>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    <?php }
                                }?>
<!--                                Star rating view with step 0.1 but stars look another-->
<!--                                <li class="yellow-text">-->
<!--                                    <input type="number" class="rating" min=0 max=5 data-size="s"-->
<!--                                           value="--><?//= $shop['rating']['average_rating'] ?><!--"-->
<!--                                           readonly data-show-clear="false" data-show-caption="false">-->
<!--                                </li>-->
                            </ul>
                        </div>
                        <ul class="card-action">
                            <li title="Товары">
                                <i class="fa fa-shopping-cart fa-3x fa-fw green-text" aria-hidden="true"></i>
                                <span><?= $shop['count_of_products'] ?></span>
                            </li>
                            <li title="Темы">
                                <i class="fa fa-list fa-3x fa-fw green-text" aria-hidden="true"></i>
                                <span><?= $shop['count_of_themes'] ?></span>
                            </li>
                            <li title="Статус">
                                <i class="fa fa-circle fa-3x fa-fw <?= ($shop['status_online']) ?
                                    'green-text' : 'light-grey-text' ?>"
                                   aria-hidden="true"></i>
                                <span><?= ($shop['status_online']) ? 'online' : 'offline' ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                </a>
                <?php } ?>

            </div>
            <div id="list" class="list">

                <?php foreach ($shops as $shop) { ?>
                <a href="/main/shop-profile?id=<?= $shop['user_id'] ?>"
                   class="collection-item avatar charter-item hoverable">
                    <div class="card horizontal small lonely-shop" data-shop-number="<?= $shop['shopNumber'] ?>">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img class="activator" style="width: 290px" src="<?= $shop['image'] ?>" alt="Shop image">
                        </div>
                        <div class="card-stacked">
                            <div class="card-content">
                                <span class="card-title"><?= $shop['name'] ?></span>
                                <p class="grey-text"><?= $shop['description'] ?></p>
                            </div>
                            <ul class="card-action">
                                <li title="Товары">
                                    <i class="fa fa-shopping-cart fa-3x fa-fw green-text" aria-hidden="true"></i>
                                    <span><?= $shop['count_of_products'] ?></span>
                                </li>
                                <li title="Темы">
                                    <i class="fa fa-list fa-3x fa-fw green-text" aria-hidden="true"></i>
                                    <span><?= $shop['count_of_themes'] ?></span>
                                </li>
                                <li title="Статус">
                                    <i class="fa fa-circle fa-3x fa-fw <?= ($shop['status_online']) ?
                                        'green-text' : 'light-grey-text' ?>"
                                       aria-hidden="true"></i>
                                    <span><?= ($shop['status_online']) ? 'online' : 'offline' ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
                <?php } ?>

            </div>
            <div class="center margin20">
                <button id="btn-more-shops" class="waves-effect waves-light btn-large transparent-color white-text
                z-depth-4 more-btn" data-count="<?= $defaultCount ?>" data-all-count="<?= $allShopsCount ?>"
                        data-load-count="<?= $defaultCount ?>">
                    <i class="fa fa-refresh fa-3x fa-fw"></i>
                    Загрузить еще
                </button>
            </div>
        </div>
    </section>

    <!-- Statistic Section -->
    <section id="facts" class="stat section z-depth-5 margin20">
        <div class="container">
            <div class="row fact-list no-margin-bot rel">
                <div class="col s12">
                    <div id="stat-slider" class="fadeOut owl-carousel">
                        <div class="stat-item">
                            <ul class="icon-block center">
                                <li>
                                    <img src="/img/stat-ico/products-icon.svg" alt="products">
                                </li>
                                <li>Товаров</li>
                                <li>
                                    <span class="timer" data-to="<?= $productsCount ?>" data-speed="2000"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="stat-item">
                            <ul class="icon-block center">
                                <li>
                                    <img  src="/img/stat-ico/theme.png" alt="mail">
                                </li>
                                <li>Темы</li>
                                <li>
                                    <span class="timer" data-to="<?= $themesCount ?>" data-speed="2000"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="stat-item">
                            <ul class="icon-block center">
                                <li>
                                    <img  src="/img/stat-ico/user.png" alt="mail">
                                </li>
                                <li>Пользователи</li>
                                <li>
                                    <span class="timer" data-to="<?= $usersCount ?>" data-speed="2000"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="stat-item">
                            <ul class="icon-block center">
                                <li>
                                    <img  src="/img/stat-ico/shop.png" alt="mail">
                                </li>
                                <li>Магазины</li>
                                <li>
                                    <span class="timer" data-to="<?= $shopsCount ?>" data-speed="2000"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php foreach ($mainCategories as $mainCategory) { ?>
        <?php if (!empty($mainCategory['child_categories'])) { ?>
            <!-- Categories Block Section -->
            <section class="category-block section margin20">
                <div class="container">
                    <div class="center-align">
                        <h2 class="border-line green-text uppercase"><?= $mainCategory['name'] ?></h2>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="collection charter-list">

                                <?php foreach ($mainCategory['child_categories'] as $child_category) { ?>
                                    <a href="/main/category?id=<?= $child_category['id'] ?>"
                                       class="collection-item avatar charter-item hoverable">
                                        <img class="chapter-icons" src="/img/ico/themes-icon.svg" alt="icons">
                                        <span class="title charter-title"><?= $child_category['name'] ?></span>
                                        <p><?= $child_category['description'] ?></p>
                                        <p>
                                            Темы:
                                            <span class="themes black-text"><?= $child_category['count_of_themes'] ?></span>
                                            Cообщения:
                                            <span class="messages black-text"><?= $child_category['count_of_comments'] ?></span>
                                        </p>
                                    </a>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }
    } ?>

    <!-- Top User Section -->
    <section class="top-user section margin20">
        <div class="container">
            <div class="center-align">
                <h2 class="border-line green-text uppercase">топ-пользователи</h2>
            </div>
           <div class="row rel no-margin-bot">
               <div class="col s12 l10 offset-l1">
                   <div id="top-user" class="owl-carousel">
                       <?php foreach ($topUsers as $topUser){ ?>
                       <div class="user-card">
                           <div class="user-image <?= ($topUser['status_online']) ? 'online-big' : 'offline-big' ?>">
                               <img width="185px" height="185px" class="circle" src="<?= $topUser['avatar'] ?>"
                                    alt="avatar">
                           </div>
                           <span class="user-name center black-text"><?= $topUser['nickname'] ?></span>
                       </div>
                       <?php } ?>
                   </div>
               </div>
               <div class="col s12">
                   <ul class="top-user-nav">
                       <li class="topUserPrevBtn">
                           <i class="large material-icons">chevron_left</i>
                       </li>
                       <li class="topUserNextBtn">
                           <i class="large material-icons">chevron_right</i>
                       </li>
                   </ul>
               </div>
           </div>
        </div>
    </section>
</main>

<!-- Admin Contacts -->
<div class="fixed-action-btn click-to-toggle admin-contacts">
    <a class="btn btn-floating btn-large green pulse"><i class="fa fa-phone fa-fw" aria-hidden="true"></i></a>
    <ul>
        <li class="waves-effect waves-light">
            <a class="btn btn-floating red tooltipped" data-position="right" data-delay="150"
               data-tooltip="<?= $adminContact['skype'] ?>">
                <i class="fa fa-skype fa-fw" aria-hidden="true"></i>
            </a>
        </li>
        <li class="waves-effect waves-light">
            <a class="btn btn-floating blue tooltipped" data-position="right" data-delay="150"
               data-tooltip="<?= $adminContact['viber'] ?>">
                <i class="fa fa-vimeo-square fa-fw" aria-hidden="true"></i>
            </a>
        </li>
    </ul>
</div>
