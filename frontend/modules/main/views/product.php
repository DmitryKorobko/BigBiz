<?php
use frontend\assets\ProductAsset;

ProductAsset::register($this);

$this->title = "Продукт";
?>
<main>
    <!-- Product -->
    <section class="product">
        <div class="container">
            <nav class="breadcrumb-box">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="/main/index" class="breadcrumb"><i class="fa fa-home" aria-hidden="true"></i></a>
                        <a href="/main/shop-profile?id=<?= $product->user_id ?>" class="breadcrumb">Товары</a>
                        <a href="/main/shop-profile/product?id=<?= $product->id ?>" class="breadcrumb">Товар</a>
                    </div>
                </div>
            </nav>
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
                                                <span class="price green-text">
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
                                        <a class="add-favorite-btn
                                        <?php if($productInFavorites) { echo('yellow-text'); } ?>"
                                           title="добавить в избранное">
                                            <i class="fa fa-star-o fa-fw fa-3x" aria-hidden="true"></i>
                                        </a>

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
                                <!--start rating-->
                                <div class="all-rating">
                                    <span class="user-rating-title">Статистика оценок пользователей</span>
                                    <div class="flex-box">
                                        <ul class="rating-first rating-list-big">
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="determinate"
                                                 style="width: <?= $productStarRating['starsPercents']['oneStar'] ?>%">

                                            </div>
                                        </div>
                                        <span class="first-number-users"><?= $productStarRating['oneStar'] ?></span>
                                    </div>
                                    <div class="flex-box">
                                        <ul class="rating-second rating-list-big">
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="determinate"
                                                 style="width: <?= $productStarRating['starsPercents']['twoStars'] ?>%">

                                            </div>
                                        </div>
                                        <span class="second-number-users"><?= $productStarRating['twoStars'] ?></span>
                                    </div>
                                    <div class="flex-box">
                                        <ul class="rating-three rating-list-big">
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="determinate"
                                                 style="width: <?= $productStarRating['starsPercents']['threeStars'] ?>%">

                                            </div>
                                        </div>
                                        <span class="three-number-users"><?= $productStarRating['threeStars'] ?></span>
                                    </div>
                                    <div class="flex-box">
                                        <ul class="rating-four rating-list-big">
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="determinate"
                                                 style="width: <?= $productStarRating['starsPercents']['fourStars'] ?>%">

                                            </div>
                                        </div>
                                        <span class="four-number-users"><?= $productStarRating['fourStars'] ?></span>
                                    </div>
                                    <div class="flex-box">
                                        <ul class="rating-five rating-list-big">
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="determinate"
                                                 style="width: <?= $productStarRating['starsPercents']['fiveStars'] ?>%">

                                            </div>
                                        </div>
                                        <span class="five-number-users"><?= $productStarRating['fiveStars'] ?></span>
                                    </div>
                                </div>
                                <!--end rating-->
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
                                    <li class="tab col s6 m4 l3">
                                        <a href="#reviews">
                                            <i class="fa fa-comments fa-fw" aria-hidden="true"></i>
                                            Отзывы
                                            <span class="num-review"><?= $feedbacksCount ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div id="description" class="col s12 product-description">
                                <p><?= $product->description ?></p>
                            </div>

                            <div id="reviews" class="col s12 product-review feedbacks-container"  data-user-id="<?= $userId ?>">
                                <a class="waves-effect waves-light z-depth-4 show-new-review-btn"
                                   data-target="review-modal">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                                <div id="rating-message"></div>

                                <?php if ((!empty($feedbacksAllDataCount)) && ($feedbacksAllDataCount > 0)) { ?>

                                <?php foreach ($productFeedbacks as $productFeedback) { ?>
                                <div id="com" class="single-comment">
                                    <ul class="author-info flex-box">
                                        <li>
                                            <span class="author-photo
                                            <?= ($productFeedback['author']['status_online']) ? 'online-sm' : 'offline-sm' ?>">
                                                <img style="width: 36px; height: 36px"
                                                     class="circle" src="<?= $productFeedback['author']['avatar'] ?>"
                                                     alt="user">
                                            </span>
                                            <span class="author-name"><?= $productFeedback['author']['name'] ?></span>
                                            <ul class="star-list">
                                                <?php for ($i = 0; $i < $productFeedback['productFeedback']['rating']; $i++) { ?>
                                                    <li class="yellow-text">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </li>
                                                <?php } if ((5 - $productFeedback['productFeedback']['rating']) > 0) {
                                                    for ($i = 0; $i < (5 - $productFeedback['productFeedback']['rating']); $i++) { ?>
                                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                                    <?php }
                                                }?>
                                            </ul>
                                        </li>
                                        <li class="comments-links small-links">
                                            <?php if ($userId != 'quest') { ?>
                                                <?php if ($userId == Yii::$app->user->getId()) { ?>
                                            <a class ="comment-delete-btn hide" title="Удалить">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>

                                            <a class ="comment-save-btn hide" title="Сохранить">
                                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                            </a>

                                            <a class ="comment-edit-btn" title="Редактировать">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                                <?php }
                                            } ?>
<!--                                            <a class="like-btn" title="Понравилось">-->
<!--                                                <i class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></i>-->
<!--                                                <span class="comments-like">10 </span>-->
<!--                                            </a>-->

                                            <span class="date">
                                                <i class="fa fa-calendar fa-fw" aria-hidden="true"></i>&nbsp;
                                                <?= Date('d.m.y', $productFeedback['productFeedback']['created_at']) ?>
                                            </span>
                                        </li>
                                    </ul>

                                    <div class="comments-text">
                                        <span><?= $productFeedback['productFeedback']['text'] ?></span>
                                    </div>

<!--                                    <div class="attachments">-->
<!--                                        <span><i class="fa fa-paperclip" aria-hidden="true"></i> Прикрепленные файлы</span>-->
<!--                                        <ul class="attachments-list">-->
<!---->
<!--                                            <li>-->
<!--                                                <a href="img/files/lion.png" class="attach-gallery"> <!--rel="gal" -->
<!--                                                    <img src="img/files/lion.png" alt="image">-->
<!--                                                </a>-->
<!--                                            </li>-->
<!---->
<!--                                        </ul>-->
<!--                                    </div>-->

                                </div>
                                <?php } ?>
                                <?php } else { ?>
                                <div id="com" class="single-comment">
                                    <div class="center-align"> <br>
                                        <h2 class="border-line black-text">Об этом товаре, пока что, нет отзывов.</h2>
                                    </div>
                                </div>
                                <?php } ?>

                                <div class="row">
                                    <div class="col s12 center" <?php if ($feedbacksDataCount >=  $feedbacksAllDataCount) {
                                        echo 'hidden=\"true\"';
                                    } ?>>
                                        <button id="btn-more-feedbacks"
                                                class="waves-effect waves-light btn-large transparent-color white-text z-depth-4 more-btn"
                                                data-all-count="<?= $feedbacksAllDataCount ?>"
                                                data-href="/main/shop-profile/more-product-feedbacks"
                                                data-count="<?= $feedbacksDataCount ?>"
                                                data-product-id="<?= $product->id ?>">
                                            <i class="fa fa-refresh fa-3x fa-fw"></i>
                                            Загрузить еще
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end tabs-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div id="review-modal" class="modal z-depth-4 review-modal-product">
    <div class="input-field col s12 center modal-title">
        <a href="/main/index"><img class="responsive-img logo-white" src="/img/logo-small.png" alt="logo"></a>
        <a class="modal-close"><i class="material-icons white-text">close</i></a>
    </div>
    <div class="modal-content">
        <div class="row no-margin-bot">
            <div class="col s12 center">
                <div class="star-item">
                    <div id="product-rating" class='starrr' data-rating=""></div>
                </div>
                <div class="new-comment">
                    <textarea class="materialize-textarea" data-length="1000" id="comment-text-field" placeholder="Ваш комментарий" rows="10" ></textarea>
                    <button type="submit" name="action"
                            class="waves-effect waves-green btn transparent-color send-comment-btn center"
                            id="save-product-feedback" data-href="/main/shop-profile/new-product-feedback"
                            data-feedback-creator-id="<?= $userId ?>"
                            data-feedback-product-id="<?= $product->id ?>">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp;
                        Отправить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
