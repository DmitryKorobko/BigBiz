<?php

/* @var $this yii\web\View */
/* @var $settings \common\models\settings\SettingsEntity */

$this->title = 'Добавить пункт настроек';
$this->params['breadcrumbs'][] = ['label' => 'Список общих настроек', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-create">
    <?= $this->render('_form', [
        'settings' => $settings
    ]) ?>
</div>
