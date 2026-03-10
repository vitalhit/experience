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
    <?= $form->field($model, 'vk_id')->hiddenInput(['value' => $booking->vk_id])->label(false); ?>    
    <?= $form->field($model, 'company_id')->hiddenInput(['value' => '229'])->label(false); ?>

    <?= $form->field($model, 'status_id')->hiddenInput(['value' => '1'])->label(false); ?>
    
    <?= $form->field($model, 'from_url')->hiddenInput(['value' => 'https://igoevent.com/spb/book/comm-fest'])->label(false); ?>
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
    <?= $form->field($model, 'brand')->textInput(['maxlength' => true, 'placeholder' => ' Краткое название вашей активности / формата'])->label(' Название активности') ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'message')->textarea(['rows' => 3, 'placeholder' => 'Укажите, что будет происходить, в каком формате (мастер-класс, перформанс, игра, тренинг и т.п.), какая цель, в чём участие людей.'])->label('Описание того, что вы проведёте на фестивале'); ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'price')->textInput(['maxlength' => true, 'placeholder' => 'Число: 1234'])->label(' Максимальное количество участников, которое вы готовы принять одновременно.') ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'info_cat')->textarea(['rows' => 3, 'placeholder' => 'Формат взаимодействия — вы работаете один раз за фестиваль или можете провести несколько занятий?'])->label('Формат взаимодействия — вы работаете один раз за фестиваль или можете провести несколько занятий?'); ?>
    </div>
    <div class="col-xs-6 col-lg-6">
    <?= $form->field($model, 'phone')->textInput(['value' => $person->phone??'','maxlength' => true, 'placeholder' => '*телефон']) ?>
    </div><div class="col-xs-6 col-lg-6">
    <?= $form->field($model, 'mail')->textInput(['value' => $person->mail??'','maxlength' => true, 'placeholder' => '*email']) ?>
    </div>
    <div class="col-xs-12 col-lg-12">

    
    <?= $form->field($model, 'link_tg')->textInput(['maxlength' => true, 'placeholder' => 'Cсылка на ваш личный телеграм. Пример: igoevent'])->label('Telegram'); ?>
    </div>

    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'info_wish')->textarea(['rows' => 3, 'placeholder' => 'Ссылки на соцсети + количество подписчиков в каждой. Это поможет нам понять, как вы коммуницируете с аудиторией.'])->label('Ссылки на соцсети + количество подписчиков в каждой. '); ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'info_job')->textarea(['rows' => 3, 'placeholder' => 'Готовы ли вы сделать от 3 до 5 публикаций о фестивале в своих соцсетях?'])->label('Готовы ли вы сделать от 3 до 5 публикаций о фестивале в своих соцсетях?'); ?>
    </div>
    <div class="col-xs-12 col-lg-12">
    <?= $form->field($model, 'info_goal')->textarea(['rows' => 3, 'placeholder' => 'Прислать ссылкой на диск или в виде файлов в телеграм @zhanmoro '])->label('Фото и видео в хорошем качестве, где видно как выглядит ваша активность в действии; или если вы работаете как спикер, вас «в процессе»; (важно: мы планируем использовать это в промо в соцсетях)'); ?>
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