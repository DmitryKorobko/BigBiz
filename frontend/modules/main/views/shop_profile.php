<?php
use frontend\assets\ShopAsset;
use davidhirtz\yii2\timeago\Timeago;

ShopAsset::register($this);

$this->title = "Профиль магазина";
?>
<main>

    <!-- Shop Info Section -->
    <section class="shop-info z-depth-3">
        <div class="container">
            <div class="row no-margin-bot">
                <div class="col s12 m4 l3">
                    <div class="shop-name center">
                        <a class="shop-logo">
                            <img src="<?= $shop['image'] ?>" width="260" height="150" alt="Shop image">
                        </a>
                        <div class="shop-title">
                            <span><?= $shop['name'] ?></span>
                        </div>
                    </div>
                    <div class="shop-review-stat">
                        <?php for ($i = 0; $i < round($rating['average_rating']); $i++) { ?>
                            <span class="shop-star"></span>
                        <?php } if ((5 - round($rating['average_rating'])) > 0) {
                            for ($i = 0; $i < (5 - round($rating['average_rating'])); $i++) { ?>
                                <span class="shop-star light-grey-text"></span>
                            <?php }
                        }?>
                        <a class="waves-effect waves-light show-old-review-btn green-text"
                           data-target="current-review-modal">
                            <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="col s12 m8 l4 about-shop">
                    <span class="title-block black-text">Описание:</span>
                    <p class="no-margin-top"><?= $shop['description'] ?></p>
                </div>
                <div class="col s12 l5">
                    <span class="title-block black-text">Контактная информация:</span>
                    <div class="row">
                        <div class="col s12 m6">
                            <ul class="card-shop-contact no-margin">
                                <li><i class="fa fa-clock-o fa-2x fa-fw" aria-hidden="true"></i>
                                    <span class="work-time">
                                        <?= $shop['work_start_time'] ?>-<?= $shop['work_end_time'] ?>
                                    </span>
                                </li>
                                <li><i class="fa fa-at fa-2x fa-fw" aria-hidden="true"></i>
                                    <span class="shop-email"><?= $email ?></span>
                                </li>
                                <li><i class="fa fa-link fa-2x fa-fw" aria-hidden="true"></i>
                                    <span class="shop-site">
                                        <?= ($shop['site_url']) ? $shop['site_url'] : 'Скоро появится' ?>
                                    </span>
                                </li>
                                <li><i class="fa fa-skype fa-2x fa-fw" aria-hidden="true"></i>
                                    <span class="shop-skype">
                                       <?= ($shop['skype']) ? $shop['skype'] : 'Скоро появится' ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col s12 m6">
                            <ul class="card-shop-contact no-margin">
                                <li><i class="fa fa-telegram fa-fw fa-2x" aria-hidden="true"></i>
                                    <span class="shop-phone">
                                        <?= ($shop['telegram']) ? $shop['telegram'] : 'Скоро появится' ?>
                                    </span>
                                </li>
                                <li><i class="fa fa-whatsapp fa-fw fa-2x" aria-hidden="true"></i>
                                    <span class="shop-phone">
                                        <?= ($shop['viber']) ? $shop['viber'] : 'Скоро появится' ?>
                                    </span>
                                </li>
                                <li><i class="fa fa-map-marker fa-fw fa-2x" aria-hidden="true"></i>
                                    <?php for ($i = 0; $i < count($cities); $i++) {
                                        if (isset($cities[$i + 1])) {
                                            echo('<span class="city"> ' . $cities[$i]['name'] . ',</span>');
                                        } else {
                                            echo('<span class="city"> ' . $cities[$i]['name'] . '</span>');
                                        }

                                    } ?>
                                </li>
                                <li class="flex-box">
                                    <?= ($statusOnline) ? '<span class="online">онлайн</span>'
                                        : '<span class="offline">офлайн</span>'
                                    ?>
                                    <a class="write-message" data-target="message-modal" title="Написать сообщение">
                                        <i class="fa fa-envelope fa-fw fa-3x" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Shop Catalog -->
    <section class="shop-catalog section margin20">
        <div class="container">
            <div class="row no-margin-bot">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab l col s4 shop-items-tab">
                            <a href="#shop-items">Товары</a>
                        </li>
                        <li class="tab с col s4 shop-review-tab">
                            <a href="#shop-review">Отзывы</a>
                        </li>
                        <li class="tab r col s4 shop-post-tab">
                            <a href="#shop-post">Темы</a>
                        </li>
                    </ul>
                </div>
            </div>


            <?php if ((!empty($productAllDataCount)) && ($productAllDataCount > 0)) { ?>
            <div class="row no-margin-bot">
                <div id="shop-items" class="col s12">
                    <div class="catalog">
                        <div id="products-container" class="row no-margin-bot">

                            <?php foreach ($products as $product) { ?>
                            <div class="col s12 m6 l3">
                                <a href="/main/shop-profile/product?id=<?= $product['id'] ?>">
                                    <div class="card card-shop-item hoverable">
                                        <div class="card-image">
                                            <img height="232" src="<?= $product['image'] ?>" alt="pic">
                                        </div>
                                        <div class="card-content">
                                            <span class="card-title"><?= $product['name'] ?></span>
                                            <ul class="number-plus-minus">
                                                <ul class="rating-list-big flex-box">
                                                    <?php
                                                    for ($i = 0; $i < round($product['average_rating']); $i++) { ?>
                                                        <li class="yellow-text">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </li>
                                                    <?php } if ((5 - round($product['average_rating'])) > 0) {
                                                        for ($i = 0; $i < (5 - round($product['average_rating'])); $i++)
                                                        { ?>
                                                            <li>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </li>
                                                        <?php }
                                                    }?>
                                                </ul>
                                                <li class="price-box center-align">
                                                    <span class="price green-text"><?= $product['price'] ?>&nbsp;UAH</span>
                                                </li>
                                                <li class="center-align">
                                                    <?= ($product['availability'])
                                                        ? '<span class="border on">в наличии</span>'
                                                        : '<span class="border off red-bg">нет в наличии</span>'
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php } ?>

                        </div>
                        <div class="row no-margin-bot">
                            <div class="col s12 center margin20">
                                <button id="btn-more-products" class="waves-effect waves-green btn-large
                                transparent-color z-depth-4 more-btn" data-all-count="<?= $productAllDataCount ?>"
                                        data-href="/main/shop-profile/more-shop-products"
                                        data-count="<?= $productDataCount ?>" data-shop-id="<?= $shop['user_id'] ?>">
                                    <i class="fa fa-refresh fa-3x fa-fw"></i>
                                    Загрузить еще
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <div class="row no-margin-bot">
                <div id="shop-items" class="col s12">
                    <div class="catalog">
                        <div id="products-container" class="row no-margin-bot">
                            <div class="center-align">
                                <h2 class="border-line black-text">У этого магазина, пока что, нет товаров.</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>


            <div class="row no-margin-bot">
                <div id="shop-review" class="col s12">

                    <div class="row">
                        <div class="col s12 m6">
                            <a class="waves-effect waves-light z-depth-4 show-new-review-btn"
                               data-target="new-review-modal">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div id="rating-message"></div>
                    </div>

                    <?php if ((!empty($reviewAllDataCount)) && ($reviewAllDataCount > 0)) { ?>

                    <div class="row">
                        <div class="col s12">
                            <ul id="reviews-container" class="shop-review-old collapsible" data-collapsible="accordion">

                                <?php foreach ($reviews as $review) { ?>
                                <li class="shop-review-item">
                                    <div class="collapsible-header flex-box">
                                        <div class="author">
                                            <a href="/main/user-profile?id=<?= $review['creator']['id'] ?>">
                                                <div class="author-photo left
                                                <?= ($review['creator']['is_online']) ? 'online-sm' : 'offline-sm' ?>">
                                                    <img src="<?= $review['creator']['avatar'] ?>" alt="User avatar"
                                                         class="circle comment-author-avatar">
                                                </div>
                                            </a>
                                            <div class="author-name">
                                                <span><?= $review['creator']['name'] ?></span>
                                            </div>
                                            <?= ($review['is_old'])
                                                ? '<div class="review-date"><span>'
                                                    . date('d.m.y G:i:s', $review['created_at'])
                                                    . '</span></div>'
                                                : '<div class="review-time"><span>'
                                                    . Timeago::tag($review['created_at'])
                                                    . '</span></div>'
                                            ?>
                                        </div>
                                        <div class="rating-list-big flex-box">
                                            <?php for ($i = 0; $i < round($review['average_rating']); $i++) { ?>
                                                <span class="shop-star"></span>
                                            <?php } if ((5 - round($review['average_rating'])) > 0) {
                                                for ($i = 0; $i < (5 - round($review['average_rating'])); $i++) { ?>
                                                    <span class="shop-star light-grey-text"></span>
                                                <?php }
                                            }?>
                                        </div>
                                    </div>
                                    <div class="collapsible-body">
                                        <div class="review-rating-small">
                                            <span class="review-name">Качество товара</span>
                                            <div class="rating-list-big flex-box">
                                                <?php for ($i = 0; $i < $review['product_rating']; $i++) { ?>
                                                    <span class="shop-star"></span>
                                                <?php } if ((5 - $review['product_rating']) > 0) {
                                                    for ($i = 0; $i < (5 - $review['product_rating']); $i++) { ?>
                                                        <span class="shop-star light-grey-text"></span>
                                                    <?php }
                                                }?>
                                            </div>
                                        </div>
                                        <div class="review-rating-small">
                                            <span class="review-name">Качество работы оператора</span>
                                            <div class="rating-list-big flex-box">
                                                <?php for ($i = 0; $i < $review['operator_rating']; $i++) { ?>
                                                    <span class="shop-star"></span>
                                                <?php } if ((5 - $review['operator_rating']) > 0) {
                                                    for ($i = 0; $i < (5 - $review['operator_rating']); $i++) { ?>
                                                        <span class="shop-star light-grey-text"></span>
                                                    <?php }
                                                }?>
                                            </div>
                                        </div>
                                        <div class="review-rating-small">
                                            <span class="review-name">Надежность магазина</span>
                                            <div class="rating-list-big flex-box">
                                                <?php for ($i = 0; $i < $review['reliability_rating']; $i++) { ?>
                                                    <span class="shop-star"></span>
                                                <?php } if ((5 - $review['reliability_rating']) > 0) {
                                                    for ($i = 0; $i < (5 - $review['reliability_rating']); $i++) { ?>
                                                        <span class="shop-star light-grey-text"></span>
                                                    <?php }
                                                }?>
                                            </div>
                                        </div>
                                        <div class="review-rating-small">
                                            <span class="review-name">Качество доставки</span>
                                            <div class="rating-list-big flex-box">
                                                <?php for ($i = 0; $i < $review['marker_rating']; $i++) { ?>
                                                    <span class="shop-star"></span>
                                                <?php } if ((5 - $review['marker_rating']) > 0) {
                                                    for ($i = 0; $i < (5 - $review['marker_rating']); $i++) { ?>
                                                        <span class="shop-star light-grey-text"></span>
                                                    <?php }
                                                }?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                        <div class="row">
                            <div class="col s12 center margin20">
                                <button id="btn-more-reviews" class="waves-effect waves-light btn-large
                                transparent-color white-text z-depth-4 more-btn"
                                        data-all-count="<?= $reviewAllDataCount ?>"
                                        data-href="/main/shop-profile/more-shop-reviews"
                                        data-count="<?= $reviewDataCount ?>"
                                        data-shop-id="<?= $shop['user_id'] ?>">
                                    <i class="fa fa-refresh fa-3x fa-fw"></i>
                                    Загрузить еще
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <div class="row no-margin-bot">
                    <div id="shop-review" class="col s12">
                        <div id="reviews-container" class="row">
                            <div class="center-align">
                                <h2 class="border-line black-text">Об этом магазине, пока что, нет отзывов.</h2>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>


            <?php if ((!empty($themeAllDataCount)) && ($themeAllDataCount > 0)) { ?>
            <div class="row no-margin-bot">
                <div id="shop-post" class="col s12">
                    <div id="themes-container" class="row">

                        <?php foreach ($themes as $theme) { ?>
                        <div class="col s12 m6 l4">
                            <div class="card card-post z-depth-3">
                                <div class="card-image">
                                    <img height="300" class="activator" src="<?= $theme['image'] ?>" alt="pic">
                                </div>
                                <div class="card-content">
                                    <div class="theme-name-box">
                                        <span class="first-theme-name"><?= $theme['main_category_name'] ?></span> /
                                        <a class="green-text" href="/main/category?id=<?= $theme['category_id'] ?>">
                                        <span class="theme-name"><?= $theme['category_name'] ?></span>
                                        </a>
                                    </div>
                                    <span class="card-title"><?= $theme['name'] ?></span>
                                    <ul class="post-links">
                                        <li><i class="fa fa-calendar fa-fw" aria-hidden="true"></i>
                                            <span class="post-data">
                                                <?= date('d.m.y', $theme['date_of_publication']) ?>
                                            </span>
                                        </li>
                                        <li><i class="fa fa-eye fa-fw" aria-hidden="true"></i>
                                            <span class="post-viewer"><?= $theme['view_count'] ?></span>
                                        </li>
                                        <li><i class="fa fa-comments fa-fw" aria-hidden="true"></i>
                                            <span class="post-comments"><?= $theme['comments_count'] ?></span>
                                        </li>
                                        <li><i class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></i>
                                            <span class="post-like"><?= $theme['count_like'] ?></span>
                                        </li>
                                        <li><i class="fa fa-thumbs-o-down fa-fw" aria-hidden="true"></i>
                                            <span class="post-dislike"><?= $theme['count_dislike'] ?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-action center-align">
                                    <a href="/main/shop-profile/theme?id=<?= $theme['id'] ?>"
                                       class="waves-effect waves-green btn white read-more-btn">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    <div class="center">
                        <button id="btn-more-themes" class="waves-effect waves-green btn-large transparent-color
                         z-depth-4 more-btn" data-all-count="<?= $themeAllDataCount ?>"
                                data-href="/main/shop-profile/more-shop-themes" data-count="<?= $themeDataCount ?>"
                                data-shop-id="<?= $shop['user_id'] ?>">
                            <i class="fa fa-refresh fa-fw"></i>
                            Загрузить еще
                        </button>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <div class="row no-margin-bot">
                <div id="shop-post" class="col s12">
                    <div id="themes-container" class="row">
                        <div class="center-align">

                            <h2 class="border-line black-text">У этого магазина, пока что, нет тем.</h2>

                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
</main>


<div id="current-review-modal" class="modal bottom-sheet review-modal z-depth-4">
    <div class="modal-content">
        <ul class="shop-review-current center">
            <li>
                <h4>Качество товара</h4>
                <div class="rating-list">
                    <?php for ($i = 0; $i < round($rating['average_product_rating']); $i++) { ?>
                        <span class="shop-star"></span>
                    <?php } if ((5 - round($rating['average_product_rating'])) > 0) {
                        for ($i = 0; $i < (5 - round($rating['average_product_rating'])); $i++) { ?>
                            <span class="shop-star light-grey-text"></span>
                        <?php }
                    }?>
                </div>
            </li>
            <li>
                <h4>Качество работы оператора</h4>
                <div class="rating-list">
                    <?php for ($i = 0; $i < round($rating['average_operator_rating']); $i++) { ?>
                        <span class="shop-star"></span>
                    <?php } if ((5 - round($rating['average_operator_rating'])) > 0) {
                        for ($i = 0; $i < (5 - round($rating['average_operator_rating'])); $i++) { ?>
                            <span class="shop-star light-grey-text"></span>
                        <?php }
                    }?>
                </div>
            </li>
            <li>
                <h4>Надежность магазина</h4>
                <div class="rating-list">
                    <?php for ($i = 0; $i < round($rating['average_reliability_rating']); $i++) { ?>
                        <span class="shop-star"></span>
                    <?php } if ((5 - round($rating['average_reliability_rating'])) > 0) {
                        for ($i = 0; $i < (5 - round($rating['average_reliability_rating'])); $i++) { ?>
                            <span class="shop-star light-grey-text"></span>
                        <?php }
                    }?>
                </div>
            </li>
            <li>
                <h4>Качество доставки</h4>
                <div class="rating-list">
                    <?php for ($i = 0; $i < round($rating['average_marker_rating']); $i++) { ?>
                        <span class="shop-star"></span>
                    <?php } if ((5 - round($rating['average_marker_rating'])) > 0) {
                        for ($i = 0; $i < (5 - round($rating['average_marker_rating'])); $i++) { ?>
                            <span class="shop-star light-grey-text"></span>
                        <?php }
                    }?>
                </div>
            </li>
        </ul>
    </div>
</div>

<div id="new-review-modal" class="modal review-modal-shop z-depth-4">
    <div class="modal-title center">
        <a href="/main/index"><img class="responsive-img logo-white" src="/img/logo-small.png" alt="logo"></a>
        <a class="modal-close"><i class="fa fa-times fa-fw fa-3x white-text" aria-hidden="true"></i></a>
    </div>
    <div class="modal-content center">
        <ul class="shop-review-new">
            <li>
                <h4>Качество товара</h4>
                <div id="product-rating" class='starrr' data-rating=""></div>
            </li>
            <li>
                <h4>Качество работы оператора</h4>
                <div id="operator-rating" class='starrr' data-rating=""></div>
            </li>
            <li>
                <h4>Надежность магазина</h4>
                <div id="reliability-rating" class='starrr' data-rating=""></div>
            </li>
            <li>
                <h4>Качество доставки</h4>
                <div id="marker-rating" class='starrr' data-rating=""></div>
            </li>
        </ul>
        <div></div>
        <button class="waves-effect waves-green btn-large transparent-color modal-action save-review-btn margin20"
           id="save-shop-review" data-href="/main/shop-profile/new-shop-review" data-rating-creator-id="<?= $userId ?>"
           data-rating-recipient-id="<?= $shop['user_id'] ?>">
            Сохранить
        </button>
    </div>
</div>

<div id="message-modal" class="modal message-modal z-depth-4">
    <div class="input-field col s12 center modal-title">
        <a href="/main/index"><img class="responsive-img logo-white" src="/img/logo-small.png" alt="logo"></a>
        <a class="modal-close"><i class="fa fa-times fa-fw fa-3x white-text" aria-hidden="true"></i></a>
    </div>
    <div class="modal-content">
        <div class="row no-margin-bot">
            <div class="col s12">
                <div class="center-align">
                    <h3 class="border-line bold">Новое сообщение</h3>
                </div>
            </div>
            <form class="message-form col s12">
                <div class="row no-margin-bot">
                    <div class="input-field col s12">
                        <textarea class="materialize-textarea" data-length="1000" id="message-text-field"
                                  placeholder="Ваше сообщение"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 center-align no-margin-bot">
                        <a href="" class="waves-effect waves-green btn green enter-btn white-text">Отправить</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

