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
    <div class="row">
    <?= $form->field($model, 'domain')->hiddenInput(['value' => 'igoevent.com'])->label(false); ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id ])->label(false) ?>
    <?= $form->field($model, 'company_id')->hiddenInput(['value' => '69'])->label(false); ?>

    <?= $form->field($model, 'status_id')->hiddenInput(['value' => '1'])->label(false); ?>
    
    <?= $form->field($model, 'from_url')->hiddenInput(['value' => 'https://igoevent.com/book/exhdogs'])->label(false); ?>
    <div class="col-xs-12 col-lg-12">
    <?php echo $form->field($model, 'event_id')->dropDownList(ArrayHelper::map($events, 'id', function ($data) { return Yii::$app->formatter->asDate($data->date); }), ['value' => $event_id??'', 'prompt' => 'Выберите ...'])->label('Выберите дату начала выставки');?>
    </div>
    <div class="col-xs-4 col-lg-4">
    <?= $form->field($model, 'second_name')->textInput(['value' => $person->second_name??'', 'maxlength' => true,'placeholder' => 'Фамилия','autocomplete'=>'on']) ?>
    </div>
    <div class="col-xs-4 col-lg-4">
    <?= $form->field($model, 'name')->textInput(['value' => $person->name??'','maxlength' => true,'placeholder' => 'Имя' ,'autocomplete'=>'on']) ?>
    </div>
    <div class="col-xs-4 col-lg-4">
    <?= $form->field($model, 'thirdname')->textInput(['maxlength' => true,'placeholder' => 'Отчество', 'value' => $person->middle_name??'']) ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'brand')->textInput(['maxlength' => true, 'placeholder' => 'Название компании/бренда']) ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'message')->textarea(['rows' => 3, 'placeholder' => 'Текст для размещение'])->label('Текст поста'); ?>
    </div>
    <div class="col-xs-12 col-lg-12">

    <?= $form->field($model, 'image')->fileInput()->label('Фотография'); ?>
    <?=  $form->field($model, 'image1')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image2')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image3')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image4')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image5')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image6')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image7')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image8')->fileInput()->label(false); ?>
    <?=  $form->field($model, 'image9')->fileInput()->label(false); ?>
    </div>
    <div class="col-xs-6 col-lg-6">
    <?= $form->field($model, 'phone')->textInput(['value' => $person->phone??'','maxlength' => true, 'placeholder' => '*телефон']) ?>
    </div><div class="col-xs-6 col-lg-6">
    <?= $form->field($model, 'mail')->textInput(['value' => $person->mail??'','maxlength' => true, 'placeholder' => '*email']) ?>
    </div>
    <div class="col-xs-12 col-lg-12">

    <?= $form->field($model, 'link_site')->textInput(['maxlength' => true, 'placeholder' => 'Cсылка на сайт. Пример: igoevent.com'])->label('Сайт'); ?>
    <?= $form->field($model, 'link_vk')->textInput(['maxlength' => true, 'placeholder' => 'Cсылка на ВК. Пример: igoevent'])->label('ВКонтакте'); ?>
    <?= $form->field($model, 'link_tg')->textInput(['maxlength' => true, 'placeholder' => 'Cсылка на телеграм. Пример: igoevent'])->label('Telegram'); ?>
    <?= $form->field($model, 'link_insta')->textInput(['maxlength' => true, 'placeholder' => 'Cсылка на ин-м. Пример: igoevent'])->label('Название в ин-м') ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.steps .agents').addClass('active');
</script>