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

    <!--<?= $form->field($model, 'company_id')->hiddenInput(['value' => $company->id??Null])->label(false); ?>-->
    <?= $form->field($model, 'company_id')->hiddenInput(['value' => '218'])->label(false); ?>

    <?= $form->field($model, 'status_id')->hiddenInput(['value' => '1'])->label(false); ?>
    

    <?php echo $form->field($model, 'event_id')->dropDownList(ArrayHelper::map($events, 'id', 'date'), ['prompt' => 'Выберите ...'])->label('Желаемая дата участия');?>

    
    <!--<?= $form->field($model, 'info_wish')->textInput(['maxlength' => true,'placeholder' => ' дата участия'])->label('Если вы хотите н') ?> -->
    <?= $form->field($model, 'second_name')->textInput(['maxlength' => true,'placeholder' => 'Ваша фамилия']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder' => 'Ваше имя']) ?>
    <?= $form->field($model, 'thirdname')->textInput(['maxlength' => true,'placeholder' => 'Ваше отчество']) ?>
    <?= $form->field($model, 'brand')->textInput(['maxlength' => true, 'placeholder' => 'Название коллектива'])->label('Название коллектива') ?>
    
    <?= $form->field($model, 'link_site')->textInput(['maxlength' => true, 'placeholder' => 'ссылки на сайт и соц. сети'])->label('Ссылка') ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 3, 'placeholder' => 'Описание коллектива и направления творечества']) ?>
    <?php /* $form->field($model, 'info')->textInput(['maxlength' => true, 'placeholder' => 'Категория вашего товара']) */?>

    <?=  $form->field($model, 'link_photo')->textarea(['rows' => 3, 'placeholder' => 'Ссылки на хранилеще с фото. Для афиши, соцсетей.
(3-4 фотографии в хорошем качестве. Квадрат, альбомная, портер)
Ссылки на аудио и видео']) ?>

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