<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;
use app\models\Goods;

/* @var $this yii\web\View */
/* @var $model app\models\Buys */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="buys-form">

    <?php $form = ActiveForm::begin();

    $goods = Goods::find()->all();
    $item = ArrayHelper::map($goods,'id','name');
    $params = [
        'prompt' => 'Выберите...'
    ];
    echo $form->field($model, 'good_id')->dropDownList($item,$params); ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

<!--    <?= $form->field($model, 'date')->textInput() ?>

     <?= $form->field($model, 'manager_id')->textInput() ?>
 -->
    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
