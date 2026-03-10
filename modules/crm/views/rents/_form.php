<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;
use app\models\Rooms;

/* @var $this yii\web\View */
/* @var $model app\models\Rents */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rents-form">

    <?php $form = ActiveForm::begin(); ?>



    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <?php $rooms = Rooms::find()->all(); 
                $items = ArrayHelper::map($rooms,'id','name');
                $params = ['prompt' => 'Выберите зал...'];
                echo $form->field($model, 'room_id')->dropDownList($items,$params);
            ?> 
        </div>

        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'summa')->textInput() ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <?= $form->field($model, 'type')->textInput() ?>
        </div>

        <div class="col-xs-12 col-lg-6">
            <?= $form->field($model, 'person_id')->textInput() ?>
        </div> 


        <div class="col-xs-12 col-lg-6">
            <?= $form->field($model, 'company_id')->textInput() ?>
        </div>

        <div class="col-xs-12 col-lg-4">
            <label class="control-label">Дата аренды</label>
            <?= DatePicker::widget([
                'model' => $model,
                'attribute' => 'date',
                'language' => 'ru',
                'readonly' => false,
                'placeholder' => 'Начало аренды',
                'class' => 'form-control',
                'clientOptions' => [
                'format' => 'YYYY-MM-DD',
                ],
            ]);
            ?>
        </div> 

        <div class="col-xs-12 col-lg-4">
            <label class="control-label">Начало аренды</label>
            <?= DatePicker::widget([
                'model' => $model,
                'attribute' => 'start',
                'language' => 'ru',
                'readonly' => false,
                'placeholder' => 'Начало аренды',
                'class' => 'form-control',
                'clientOptions' => [
                'format' => 'LT',
                ],
            ]);
            ?>
        </div> 

        <div class="col-xs-12 col-lg-4">
            <label class="control-label">Окончание аренды</label>
            <?= DatePicker::widget([
                'model' => $model,
                'attribute' => 'end',
                'language' => 'ru',
                'readonly' => false,
                'placeholder' => 'Окончание аренды',
                'class' => 'form-control',
                'clientOptions' => [
                'format' => 'LT',
                ],
                ]);
            ?>
        </div>
    </div> 


    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
