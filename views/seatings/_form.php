<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Places;

/* @var $this yii\web\View */
/* @var $model app\models\Seatings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seatings-form">

    <?php $form = ActiveForm::begin([
                'options' => ['enctype'=>'multipart/form-data']
        ]);


    $places = Places::find()->all(); 
    $items = ArrayHelper::map($places,'id','name');
    $params = ['prompt' => 'Выберите ...'];

    echo $form->field($model, 'place_id')->dropDownList($items,$params); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'info')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
