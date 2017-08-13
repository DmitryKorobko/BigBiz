<?php

/* @var $this yii\web\View */
/* @var $theme \common\models\theme\ThemeEntity */

$this->title = 'Обновить тему: ' . $theme->name;
$this->params['breadcrumbs'][] = ['label' => 'Список тем', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить тему';
?>
<div class="personal-info-update">
    <?=
        $this->render('_form', [
            'theme' => $theme
        ]);
    ?>
</div>