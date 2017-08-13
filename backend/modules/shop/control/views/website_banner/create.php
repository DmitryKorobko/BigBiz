<?php

/* @var $this yii\web\View */
/* @var $banner \common\models\website_banner\WebsiteBannerEntity */

$this->title = 'Добавить баннер';
$this->params['breadcrumbs'][] = ['label' => 'Список баннеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-create">
    <?= $this->render('_form', [
        'banner' => $banner
    ]) ?>
</div>
