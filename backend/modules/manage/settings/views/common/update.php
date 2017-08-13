<?php

/* @var $this yii\web\View */
/* @var $settings \common\models\settings\SettingsEntity */

$this->title = 'Обновить пункт настроек';
$this->params['breadcrumbs'][] = ['label' => 'Список общих настроек', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить пункт настроек';
?>
<div class="personal-info-create">
    <?= $this->render('_form', [
        'settings' => $settings
    ]) ?>
</div>