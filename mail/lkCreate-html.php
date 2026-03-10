<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user mdm\admin\models\User */

$resetLink = Url::to(['site/reset-password','token'=>$user->password_reset_token], true);
?>
<div class="password-reset">
    <p>Добрый день, <?= Html::encode($user->username) ?>,</p>

    <p>Для вас создан личный кабинет, в котором вы найдете все купленные билеты:</p>

    <p>Сайт: <a href="https://igoevent.com/">igoevent.com</a></p>

    <p>Логин: <?= Html::encode($user->username) ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
