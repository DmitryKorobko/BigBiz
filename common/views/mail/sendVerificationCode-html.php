<?php
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $user common\models\user\UserEntity */
?>
<div class="recovery-password">
    <p>Уважаемый клиент <?= Html::encode($email) ?>,</p>

    <p>Чтобы закончить регистрацию и пройти верификацию вашего аккаунта,
        необходимо ввести данный код <?= $verificationCode ?> и Информацию о себе в Профиле приложения</p>
</div>

