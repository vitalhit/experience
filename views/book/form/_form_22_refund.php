<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Contragent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contragent-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'company_id')->hiddenInput(['value' => '1'])->label(false); ?>

    <?= $form->field($model, 'status_id')->hiddenInput(['value' => '1'])->label(false); ?>
    
    <?= $form->field($model, 'from_url')->hiddenInput(['value' => 'https://igoevent.com/book/refund'])->label(false); ?>

    <?= $form->field($model, 'event_id')->hiddenInput(['value' => $event_id??Null])->label(false); ?>

    
    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true,'placeholder' => 'Ваша фамилия']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder' => 'Ваше имя']) ?>
    <?= $form->field($model, 'thirdname')->textInput(['maxlength' => true,'placeholder' => 'Ваше отчество']) ?>
    <?= $form->field($model, 'message')->textarea(['rows' => 3, 'placeholder' => 'Причина возврата']) ?>
    
    <?= $form->field($model, 'image')->fileInput()->label('Скриншот или скан заявления'); ?>
    <?= $form->field($model, 'mail')->textInput(['maxlength' => true, 'placeholder' => '*email']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => '*телефон']) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.steps .agents').addClass('active');
</script>