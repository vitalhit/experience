<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\Companies;
use app\models\Places;
use app\models\Users;
use app\models\CompanyUser;


/* @var $this yii\web\View */
/* @var $model app\models\Rooms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'room_id')->hiddenInput(['value' => $room->id])->label(false) ?>
    <?= $form->field($model, 'person_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'money')->hiddenInput()->label(false) ?>

    <?= $form->field($person, 'mail')->textInput() ?>
    <?= $form->field($person, 'name')->textInput() ?>
    <?= $form->field($person, 'second_name')->textInput() ?>
    <?= $form->field($person, 'phone')->textInput() ?>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>

    <?php ActiveForm::end(); ?>

</div>
