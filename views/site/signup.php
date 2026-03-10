<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */
?>

<div class="open-form">
    <div class="container">
        <div class="col-xs-12">
            <a name="reg"></a>
            <h2>Регистрируйся<br><small>и получи <span>бесплатно</span> полный доступ к сервису</small></h2>

            <?= Html::errorSummary($model)?>
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'form-static-page']); ?>
            
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-red', 'name' => 'signup-button']) ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>