<?php
$this->title = "Тема";
?>
<main>
    <!-- Post Section -->
    <section class="section-post">
        <div class="single-post">
            <div class="post-header post-image bg-img" style="background: url(<?= $theme->image ?>) no-repeat center;">
                <span class="single-post-title center"><?= $theme->name ?></span>
                <span
                        class="post-date center white-text"><?= Date('d M.y', $theme->date_of_publication) ?>
                </span>
            </div>
            <div class="container">
                <nav class="breadcrumb-box">
                    <div class="nav-wrapper">
                        <div class="col s12">
                            <a href="/main/index" class="breadcrumb"><i class="fa fa-home" aria-hidden="true"></i></a>
                            <a href="/main/category?id=<?= $theme->category_id ?>" class="breadcrumb">Темы</a>
                            <a href="/main/category/theme?id=<?= $theme->id ?>" class="breadcrumb">Статья</a>
                        </div>
                    </div>
                </nav>
                <div class="row">
                    <div class="col s12 single-post-content">
                        <p><?= $theme->description ?></p>
                        <div class="post-image">
                            <img class="responsive-img" src="<?= $theme->image ?>" alt="image">
                        </div>
                        <ul class="single-post-links">
                            <li class="comments-links big-links">
                                <a class="add-favorite-btn <?php if($themeInFavorites) { echo('yellow-text'); } ?>"
                                   title="Добавить в избранное">
                                    <i class="fa fa-star-o fa-fw fa-2x" aria-hidden="true"></i>
                                </a>
                                <a class="post-like-btn
                                <?php if((!empty($themeLikeDislike)) && ($themeLikeDislike['like'])) {
                                    echo ('green-text');
                                } ?>" title="Понравилось">
                                    <i class="fa fa-thumbs-o-up fa-2x fa-fw" aria-hidden="true"></i>
                                    <span class="post-like"><?= $themeCountLike ?></span>
                                </a>
                                <a class="post-dislike-btn
                                <?php if((!empty($themeLikeDislike)) && (!$themeLikeDislike['like'])) {
                                    echo ('red-text');
                                } ?>" title="Не понравилось">
                                    <i class="fa fa-thumbs-o-down fa-2x fa-fw" aria-hidden="true"></i>
                                    <span class="post-dislike"><?= $themeCountDislike ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php if ($userId !== 'quest') { ?>
                        <div class="col s12 single-post-comments">
                            <div class="comments">
                                <ul class="comments-header flex-box">
                                    <li class="title-comments green-text left">Комментарии
                                        <span class="num-comments">(<?= $theme->comments_count ?>)</span>
                                    </li>
                                </ul>
                                <div class="comments-list">


                                    <?php foreach ($comments as $comment) { ?>
                                        <div class="single-comment">
                                            <ul class="author-info flex-box">
                                                <li>
                                                    <a class="author-photo
                                            <?php if ($comment['author']['status_online']) {
                                                        echo('online-sm');
                                                    } else {
                                                        echo('offline-sm');
                                                    }  ?>">
                                                        <img class="circle comment-author-avatar"
                                                             src="<?= $comment['author']['avatar'] ?>" alt="user">
                                                    </a>
                                                    <span class="author-name"><?= $comment['author']['name'] ?>, </span>
                                                    <span class="date">
                                                <i class="fa fa-calendar fa-fw" aria-hidden="true"></i>
                                                        <?= Date('d.m.y', $comment['comment']['created_at']) ?>
                                            </span>
                                                </li>
                                                <li class="comments-links small-links">
                                                    <a class ="comment-delete-btn hide" title="Удалить">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a>
                                                    <a class ="comment-save-btn hide" title="Сохранить">
                                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    </a>
                                                    <a class ="comment-edit-btn" title="Редактировать">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="answer-btn" title="Ответить">
                                                        <i class="fa fa-reply fa-fw" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="like-btn <?php if ($comment['comment']['liked']) {
                                                        echo('green-text');
                                                    } ?>"
                                                       title="Понравилось">
                                                        <i class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></i>
                                                        <span class="comments-like"><?= $comment['comment']['count_like'] ?>
                                                </span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="comments-text">
                                                <div class="<?php if(strlen($comment['comment']['text']) > 280) {
                                                    echo('spoiler');
                                                } ?>">
                                                    <span><?= $comment['comment']['text'] ?></span>
                                                </div>
                                                <?php if(strlen($comment['comment']['text']) > 280) {
                                                    echo ('<a class="show-hide-btn">Показать полностью…</a>');
                                                } ?>
                                            </div>
                                            <div class="attachments">
                                        <span>
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                            Прикрепленные файлы
                                        </span>
                                                <ul class="attachments-list">
                                                    <li>
                                                        <a href="/img/files/lion.png" class="attach-gallery"> <!--rel="gal" -->
                                                            <img src="/img/files/lion.png" alt="image">
                                                        </a>
                                                    </li>
                                                    <li class="add-document hide">
                                                        <div class="file-upload-wrap">
                                                            <input type="file" class="input-file">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <div class="center" <?php if ($dataCount >=  $allDataCount) {
                                        echo 'hidden=\"true\"';
                                    } ?>>>
                                        <a class="waves-effect waves-light btn-large transparent-color white-text z-depth-4 more-btn">
                                            <i class="fa fa-refresh fa-3x fa-fw"></i>
                                            Загрузить еще
                                        </a>
                                    </div>


                                </div>
                                <div class="new-comment">
                                <textarea class="materialize-textarea" data-length="1000" id="comment-text-field"
                                          placeholder="Ваш комментарий"></textarea>
                                    <div class="button-box hide">
                                        <button type="submit" name="action"
                                                class="waves-effect waves-green btn transparent-color send-comment-btn left">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp;
                                            Отправить
                                        </button>
                                        <ul class="attachments-list">
                                            <li class="add-document">
                                                <div class="file-upload-wrap">
                                                    <input type="file" class="input-file">
                                                </div>
                                            </li>
                                        </ul>
                                        <!--<a class="comment-add-image waves-effect waves-green"><i class="material-icons green-text">add_a_photo</i></a>-->
                                    </div>

                                    <!--<div class="reply-comment hide">-->
                                    <!--ответить <a class="reply-user-name"></a> <a class="delete-btn-new-comment"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>
