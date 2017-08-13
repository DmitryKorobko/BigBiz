<?php

/* @var $this yii\web\View */

$this->title = 'Moderator Panel';
?>
<div class="site-index">
    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>
                            <?= $count_of_own_themes ?>
                        </h3>
                        <p>
                            Темы
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-speakerphone"></i>
                    </div>
                    <a href="/admin/moderator/content/theme" class="small-box-footer">
                        Подробнее <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>
                            <?= $count_of_own_comments ?>
                        </h3>
                        <p>
                            Комментарии
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-chatbubble"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Подробнее <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            <?= $count_of_comments_for_themes ?>
                        </h3>
                        <p>
                            Комментарии к темам
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-chatbubbles"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Подробнее <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
