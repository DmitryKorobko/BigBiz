<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\BackendUserEntity */

$this->title = 'Добавить администратора сайта';
$this->params['breadcrumbs'][] = ['label' => 'Администраторы сайта', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
