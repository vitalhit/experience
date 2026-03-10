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

    <!--<?= $form->field($model, 'company_id')->hiddenInput(['value' => $company->id])->label(false); ?>-->
    <?= $form->field($model, 'company_id')->hiddenInput(['value' => '28'])->label(false); ?>
    

    <?php echo $form->field($model, 'event_id')->dropDownList(ArrayHelper::map($events, 'id', 'date'), ['prompt' => 'Выберите ...'])->label('Желаемая дата участия');?>

    
    <!--<?= $form->field($model, 'info_wish')->textInput(['maxlength' => true,'placeholder' => ' дата участия'])->label('Если вы хотите н') ?> -->
    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true,'placeholder' => 'Ваше имя']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder' => 'Ваше имя']) ?>
    <?= $form->field($model, 'thirdname')->textInput(['maxlength' => true,'placeholder' => 'Ваше имя']) ?>
    <?= $form->field($model, 'brand')->textInput(['maxlength' => true, 'placeholder' => 'Название вашего бренда']) ?>
    <?= $form->field($model, 'info_cat')->textInput(['maxlength' => true, 'placeholder' => 'Категория вашего товара']) ?>

    <?= $form->field($model, 'mail')->textInput(['maxlength' => true, 'placeholder' => '*email']) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => '*телефон']) ?>


    <?= $form->field($model, 'info')->textarea(['rows' => 3, 'placeholder' => 'АКТИВНАЯ ссылка на группу вконтакте, инстаграм, сайт, другой веб-ресурс (т.е. не просто название аккаунта @foursiz, а именно активная ссылка) ']) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.steps .agents').addClass('active');
</script>