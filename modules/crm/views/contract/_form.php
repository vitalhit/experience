<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="companies-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'person_id')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->inn)) echo $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->kpp)) echo $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->ogrn)) echo $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->jaddress)) echo $form->field($model, 'jaddress')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->faddress)) echo $form->field($model, 'faddress')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->man)) echo $form->field($model, 'man')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->position)) echo $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->nds)) echo $form->field($model, 'nds')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->bank)) echo $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->bik)) echo $form->field($model, 'bik')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->korr)) echo $form->field($model, 'korr')->textInput(['maxlength' => true]) ?>

    <?php if(empty($model->raschet)) echo $form->field($model, 'raschet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->hiddenInput(['value' => '1'])->label(false) ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $('.steps .conts').addClass('active');
</script>