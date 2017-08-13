<?php
use frontend\assets\CategoryAsset;

CategoryAsset::register($this);

$this->title = "Список тем";
?>
<main>
    <!-- News list -->
    <section class="news-list section margin20">
        <div class="container">


            <div class="center-align">
                <h2 class="border-line green-text uppercase"><?= $categoryName ?></h2>
            </div>

            <nav class="breadcrumb-box">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="/main/index" class="breadcrumb"><i class="fa fa-home" aria-hidden="true"></i></a>
                        <a href="/main/category?id=<?= $categoryId ?>" class="breadcrumb"><?= $categoryName ?></a>
                    </div>
                </div>
            </nav>

            <?php if ((!empty($allDataCount)) && ($allDataCount > 0)) { ?>
            <div class="row">
                <div class="col s12">
                    <div id="themes-container" class="row">

                        <?php foreach ($themes as $theme) { ?>
                        <div class="col s12 m6 l4">
                            <div class="card card-post z-depth-3">
                                <div class="card-image">
                                    <?php if (!empty($theme['image'])) { ?>
                                    <img height="300px" class="activator" src="<?= $theme['image'] ?>" alt="pic">
                                    <?php } else { ?>
                                        <img src="/img/article/no-pic.png" alt="pic">
                                    <?php } ?>
                                </div>
                                <div class="card-content">
                                    <span class="card-title"><?= $theme['name'] ?></span>
                                    <ul class="post-links">
                                        <li>
                                            <i class="fa fa-calendar fa-fw" aria-hidden="true"></i>
                                            <span class="post-data">
                                                <?= date('d.m.y', $theme['date_of_publication']) ?>
                                            </span>
                                        </li>
                                        <li>
                                            <i class="fa fa-eye fa-fw" aria-hidden="true"></i>
                                            <span class="post-viewer"><?= $theme['view_count'] ?></span>
                                        </li>
                                        <li>
                                            <i class="fa fa-comments fa-fw" aria-hidden="true"></i>
                                            <span class="post-comments"><?= $theme['comments_count'] ?></span>
                                        </li>
                                        <li>
                                            <i class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></i>
                                            <span class="post-like"><?= $theme['count_like'] ?></span>
                                        </li>
                                        <li>
                                            <i class="fa fa-thumbs-o-down fa-fw" aria-hidden="true"></i>
                                            <span class="post-dislike"><?= $theme['count_dislike'] ?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-action center-align">
                                    <a href="/main/category/theme?id=<?= $theme['id'] ?>"
                                       class="waves-effect waves-green btn white read-more-btn">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    <div class="center" <?php if ($dataCount >=  $allDataCount) {
                        echo 'hidden=\"true\"';
                    } ?>>
                        <button id="btn-more" class="waves-effect waves-green btn-large transparent-color z-depth-4
                        more-btn" data-all-count="<?= $allDataCount ?>" data-href="/main/category/more-category-themes"
                        data-count="<?= $dataCount ?>" data-category-id="<?= $categoryId ?>">
                            <i class="fa fa-refresh fa-fw"></i>
                            Загрузить ещё
                        </button>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <div class="center-align">
                    <h2 class="border-line black-text">В этой категории ещё не создано ни одной темы.</h2>
                </div>
            <?php } ?>
        </div>
    </section>
</main>
