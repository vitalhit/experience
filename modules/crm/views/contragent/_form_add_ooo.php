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

    <?= $form->field($model, 'company_id')->hiddenInput(['value' => $company->id])->label(false); ?>
    <div class="row">
        <div class="col-xs-4 col-lg-4">
         <?= $form->field($model, 'company_type')->textInput(['maxlength' => true,'placeholder' => 'ИП / ООО / физическое лицо', 'value
'=>'ООО']) ?>
        </div>
        <div class="col-xs-4 col-lg-4">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder' => 'ООО «Хороший Проект»', 'value
'=>'ООО ']) ?>
        </div>
        <div class="col-xs-4 col-lg-4">
        
        <?= $form->field($model, 'status_fill')->dropDownList([
                        '0' => 'Заполнить',
                        '100' => 'Заполнено: полностью',
                        '' => 'Не известно',
                        '-1' => 'Не те реквизиты',
                        '-100' => 'Rename', 
                        
                    ]);?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-8 col-lg-8">
        <?= $form->field($model, 'name_long')->textInput(['maxlength' => true,'placeholder' => 'индивидуальный предприниматель Хитров Виталий Глебович', 'value
'=>'ООО ']) ?>
        </div>
        <div class="col-xs-4 col-lg-4">
        <?= $form->field($model, 'brand')->textInput(['maxlength' => true,'placeholder' => 'Название бренда']) ?>  
        </div>
    </div>
    <div class="row">
    
        <div class="col-xs-6 col-lg-6">
    <?= $form->field($model, 'jaddress')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-6 col-lg-6">
    <?= $form->field($model, 'faddress')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>
        </div>
       
        <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'raschet')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'bik')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'korr')->textInput(['maxlength' => true]) ?> 
        </div>
         <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'man')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-2 col-lg-2">
    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <h4>Для договора</h4>
    <?= $form->field($model, 'man_genitive')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'doc_basis')->textInput(['maxlength' => true, 'placeholder' => 'устава']) ?>
    
    <h4>Можно не заполнять</h4>
    <?= $form->field($model, 'status_edm')->dropDownList([
                        '0' => 'Не известно',
                        '1' => 'Исползуем',
                        '-1' => 'Не используем',
                    ]);?>
    <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nds')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'global')->textInput() ?>

    
    
    <h4>Для физических лиц и музыкальных групп</h4>
    <?= $form->field($model, 'doc_type')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'doc_place')->textInput(['maxlength' => true]) ?>
   
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

    <?= $form->field($model, 'doc_place_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birth_place')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label" for="events-date">Дата рождения</label>

                    <?= DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'birth_date',
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

    <?= $form->field($model, 'pseudonym')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pseudonym_en')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.steps .agents').addClass('active');
</script>