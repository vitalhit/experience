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
    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder' => 'Пример: ИП Хитров Виталий Глебович']) ?>
    <?= $form->field($model, 'company_type')->dropDownList([
                        'ООО' => 'ООО',
                        'индивидуальный предприниматель' => 'индивидуальный предприниматель',
                        'нет, подходящего варианта' => 'нет, подходящего варианта',
                    ]);?>
    <?= $form->field($model, 'name_long')->textInput(['maxlength' => true,'placeholder' => 'Пример: общество с ограниченой ответсвенностью Хороший Проект']) ?>

    

    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'man')->textInput(['maxlength' => true, 'placeholder' => 'ФИО директора']) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true, 'placeholder' => 'директора / генеральный директор — как в уставе']) ?>

    <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jaddress')->textInput(['maxlength' => true, 'placeholder' => 'Индекс, Город, Ул., Дом, помещение/квартира']) ?>

    <?= $form->field($model, 'faddress')->textInput(['maxlength' => true, 'placeholder' => 'Индекс, Город, Ул., Дом, помещение/квартира']) ?>
 
    
    <?= $form->field($model, 'bank')->textInput(['maxlength' => true, 'placeholder' => 'Наименование банка']) ?>

    <?= $form->field($model, 'raschet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bik')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'korr')->textInput(['maxlength' => true]) ?> 

    
    <?= $form->field($model, 'status_edm')->dropDownList([
                        '0' => 'Не знаю, что это',
                        '1' => 'Исползуем',
                        '-1' => 'Не используем',
                    ]);?>

    <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nds')->dropDownList([
                        'Без НДС' => 'Без НДС',
                        'С НДС' => 'С НДС',
                        '' => 'Не знаю, что это',
                    ]);?>
    <!-- <?= $form->field($model, 'status')->textInput() ?> -->

    <?= $form->field($model, 'info')->textarea(['rows' => 3, 'placeholder' => 'Пожелание по месту или номер места, если вы уже участвуете.']) ?>

    <!--<?= $form->field($model, 'global')->textInput() ?>-->

    <h4>Для договора</h4>
    <?= $form->field($model, 'man_genitive')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'doc_basis')->textInput(['maxlength' => true, 'placeholder' => 'устава']) ?>
    <!--
    <h4>Для физических лиц и музыкальных групп</h4>
    <?= $form->field($model, 'doc_type')->dropDownList(['паспорт' => 'паспорт']);?>
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

    <?= $form->field($model, 'doc_place_number')->textInput(['maxlength' => true, 'placeholder' => 'NNNNNN — для российского паспорта: шесть цифр без пробелов']) ?>

    <?= $form->field($model, 'birth_place')->textInput(['maxlength' => true]) ?>

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
    
    -->
    <!--
    <?= $form->field($model, 'pseudonym')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pseudonym_en')->textInput(['maxlength' => true]) ?>
    -->

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.steps .agents').addClass('active');
</script>