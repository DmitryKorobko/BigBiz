<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

/* @var $content string */
?>
<aside class="right-side">
    <?php if (isset($this->params['breadcrumbs'])) : ?>
        <section class="content-header">
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs']
            ]); ?>
        </section>
    <?php endif ?>

    <section class="content">
        <div id="status-message"></div>
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</aside>