<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\BackendUserEntity */

$this->title = 'Обновить администратора: ' . $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Администраторы сайта', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить администратора';
?>
<div class="personal-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
