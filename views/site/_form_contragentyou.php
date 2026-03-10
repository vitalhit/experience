<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Contragent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contragent-form">

    <?php $form = ActiveForm::begin(); ?>

   
    <?= $form->field($model, 'company_id')->hiddenInput(['value' => '28'])->label(false); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder' => 'Пример: Иванова Иоанна Ивановна'])->label('Ваши ФИО') ?>


    <?= $form->field($model, 'company_type')->hiddenInput(['value' => 'физическое лицо'])->label(false); ?>
    <?= $form->field($model, 'inn')->textInput(['maxlength' => true, 'placeholder' => 'не обязательно заполнять']) ?>    

    <?= $form->field($model, 'position')->hiddenInput(['value' => 'физическое лицо'])->label(false); ?>

    <?= $form->field($model, 'jaddress')->textInput(['maxlength' => true, 'placeholder'=>'Индекс, Город, Ул., Дом, помещение/квартира — прописка для физических лиц'])->label("Место регистрации"); ?>
    
    <?= $form->field($model, 'man_genitive')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'doc_type')->hiddenInput(['value' =>  'паспорт'])->label(false);?>
    <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true, 'placeholder'=>'NNNNNNNNNN — серия и номер паспорта без пробелов']) ?>
    <?= $form->field($model, 'doc_place')->textInput(['maxlength' => true, 'placeholder'=>'Учереждение, выдавшее паспорт']) ?>
   
    <div class="form-group">
        <label class="control-label" for="events-date">Дата выдачи документа</label>

                    <?= DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'doc_date',
                        'language' => 'ru',
                        'readonly' => false,
                        'placeholder' => 'Выберите дату',
                        'class' => 'form-control',
                        'clientOptions' => [
                            'format' => 'YYYY-MM-DD LT',
                        //'minDate' => '2015-08-10',
                        //'maxDate' => '2015-09-10',
                        ],
                    ]);?>
    </div>

    <?= $form->field($model, 'doc_place_number')->textInput(['maxlength' => true, 'placeholder' => 'NNNNNN — для российского паспорта: шесть цифр без пробелов']) ?>

    <?= $form->field($model, 'birth_place')->textInput(['maxlength' => true, 'placeholder' => 'Место рождения']) ?>

    <div class="form-group">
        <label class="control-label" for="events-date">Дата рождения</label>

                    <?= DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'birth_date',
                        'language' => 'ru',
                        'readonly' => false,
                        'placeholder' => 'YYYY-MM-DD — Выберите дату',
                        'class' => 'form-control',
                        'clientOptions' => [
                            'format' => 'YYYY-MM-DD',
                        //'minDate' => '2015-08-10',
                        //'maxDate' => '2015-09-10',
                        ],
                    ]);?>
    </div>

    <?= $form->field($model, 'info')->textarea(['rows' => 3, 'placeholder' => 'Пожелание по месту или номер места, если вы уже участвуете.']) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.steps .agents').addClass('active');
</script>