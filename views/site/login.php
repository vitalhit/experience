<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div> <!-- class="site-login" -->
    <div class="col-xs-12 col-sm-4 col-sm-offset-4">
        <h2 class="mt0">Войти</h2>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <div style="float:left">
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <a href="/site/request-password-reset">Забыли пароль?</a>
        </div>
        <div class="form-group clear">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-success pull-right', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>

        <?= yii\authclient\widgets\AuthChoice::widget([ 'baseAuthUrl' => ['site/auth'], 'popupMode' => false]) ?>

    </div>
</div>
