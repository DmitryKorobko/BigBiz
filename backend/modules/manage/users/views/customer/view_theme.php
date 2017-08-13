<?php
use backend\assets\ThemeAsset;

ThemeAsset::register($this);

$this->title = "Тема";
?>
<main>
    <!-- Post Section -->
    <section class="section-post">
        <div class="single-post">
            <div class="post-header post-image bg-img" style="background: url(<?= $theme->image ?>) no-repeat center; text-align: center">
                <span  class="single-post-title center"><?= $theme->name ?></span>
                <span class="post-date center white-text" style="text-align: center">
                    <?= Date('d M.y', $theme->date_of_publication) ?>
                </span>
            </div>
            <div class="container">
                <nav class="breadcrumb-box">
                    <div class="nav-wrapper">
                        <div class="col s12">
                            <h3>
                                <a href="../../../" ><i class="fa fa-home" aria-hidden="true"></i></a>
                                <a href="view?id=<?= $theme->user_id ?>">/ Профиль пользователя</a>
                                <a href="view-theme?id=<?= $theme->id ?>">/ Тема</a>
                            </h3>
                        </div>
                    </div>
                </nav>
                <div class="row">
                    <div class="col s12 single-post-content">
                        <p><?= $theme->description ?></p>
                        <div class="post-image">
                            <img class="responsive-img" src="<?= $theme->image ?>" alt="image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>