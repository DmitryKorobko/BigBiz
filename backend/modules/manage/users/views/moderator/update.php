<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\BackendUserEntity */

$this->title = 'Обновить модератора: ' . $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Модераторы сайта', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить модератора';
?>
<div class="personal-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
