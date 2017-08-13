<?php

/* @var $this yii\web\View */
/* @var $banner \common\models\website_banner\WebsiteBannerEntity */

$this->title = 'Обновить баннер';
$this->params['breadcrumbs'][] = ['label' => 'Список баннеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить баннер';
?>
<div class="personal-info-update">
    <?= $this->render('_form', [
        'banner' => $banner
    ]); ?>
</div>