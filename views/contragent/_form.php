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
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder' => 'ИП Хитров Виталий Глебович']) ?>
    <?= $form->field($model, 'name_long')->textInput(['maxlength' => true,'placeholder' => 'индивидуальный предприниматель Хитров Виталий Глебович']) ?>
    <?= $form->field($model, 'company_type')->textInput(['maxlength' => true,'placeholder' => 'индивидуальный предприниматель/физическое лицо/ООО']) ?>
    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'man')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jaddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'faddress')->textInput(['maxlength' => true]) ?>
 
    
    <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'raschet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bik')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'korr')->textInput(['maxlength' => true]) ?> 

    <h4>Можно не заполнять</h4>
    <?= $form->field($model, 'status_edm')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nds')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'global')->textInput() ?>

    <h4>Для договора</h4>
    <?= $form->field($model, 'man_genitive')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'doc_basis')->textInput(['maxlength' => true, 'placeholder' => 'устава']) ?>
    
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