<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Categoryevents */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categoryevents-form">

    <?php $form = ActiveForm::begin(); ?>
    	<?= $form->field($model, 'company_id')->hiddenInput(['value' => Yii::$app->user->identity->company_active])->label(false) ?>

        <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать и продолжить <span class="glyphicon glyphicon-chevron-right"></span>' : 'Обновить', ['class' => 'btn btn-success pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
