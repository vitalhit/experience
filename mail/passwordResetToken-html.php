<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user mdm\admin\models\User */

$resetLink = Url::to(['site/reset-password','token'=>$user->password_reset_token], true);
?>
<div class="password-reset">
    <p>Добрый день, <?= Html::encode($user->username) ?>,</p>

    <p>Чтобы восстановить пароль, пройдите по ссылке:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
