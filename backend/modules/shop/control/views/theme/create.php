<?php

/* @var $this yii\web\View */
/* @var $theme \common\models\theme\ThemeEntity */

$this->title = 'Добавить тему';
$this->params['breadcrumbs'][] = ['label' => 'Список тем', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-create">
    <div class="alert alert-info alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        Для отображения темы в собственном профиле не указывайте категорию!!!
    </div>
    <?=
        $this->render('_form', [
            'theme' => $theme
        ]);
    ?>
</div>
