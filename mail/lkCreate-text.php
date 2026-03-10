<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user mdm\admin\models\User */

$resetLink = Url::to(['site/reset-password','token'=>$user->password_reset_token], true);
?>
Добрый день, <?= $user->username ?>,

Для вас создан личный кабинет, в котором вы найдете все купленные билеты:

Сайт: <a href="https://igoevent.com/">igoevent.com</a>

Логин: <?= Html::encode($user->username) ?>

<?= $resetLink ?>
