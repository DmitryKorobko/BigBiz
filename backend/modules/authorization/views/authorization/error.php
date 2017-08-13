<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<!-- Main content -->
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3 style="color: #ffffff"><?= $name ?></h3>

            <p style="color: #ffffff">
                <?= nl2br(Html::encode($message)) ?>
            </p>

            <p>
                Произошла ошибка во время обработки вашего запроса.<br>
                Пожалуйста, свяжитесь с нами если Вы думаете, что это ошибка сервера.<br>
                Вы можете вернутся на <a style="color: chartreuse" href='<?= Yii::$app->homeUrl ?>'>главную страницу</a> администрирования.
            </p>


        </div>
    </div>

</section>
