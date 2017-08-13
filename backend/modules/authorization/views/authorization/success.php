<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Регистрация';
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-success">
            <strong>Поздравляю!</strong>
            До окончания регистрации совсем немного. Проверьте электронную почту, указанную при регистрации,
            и активируйте аккаунт.
        </div>
        <div class="col-md-5 col-md-offset-3">
            <?= Html::a('Вернутся к авторизации', ['/'], [
                'class' => 'waves-effect waves-light btn green enter-btn white-text',
                'name'  => 'back-button'
            ]) ?>
        </div>
    </div>
</div>