<?php

/* @var $this yii\web\View */
/* @var $theme \common\models\theme\ThemeEntity */

$this->title = 'Добавить тему';
$this->params['breadcrumbs'][] = ['label' => 'Список тем', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-create">
    <?=
        $this->render('_form', [
            'theme' => $theme
        ]);
    ?>
</div>
