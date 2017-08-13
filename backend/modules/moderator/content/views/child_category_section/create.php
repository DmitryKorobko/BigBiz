<?php

/* @var $this yii\web\View */
/* @var $category \common\models\main_category_section\MainCategorySectionEntity */

$this->title = 'Добавить категорию';
$this->params['breadcrumbs'][] = ['label' => 'Список категорий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-create">
    <?= $this->render('_form', [
        'category' => $category
    ]) ?>
</div>
