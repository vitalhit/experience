<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Goods;
use app\models\Items;
use app\models\Persons;


/* @var $this yii\web\View */
/* @var $model app\models\Sells */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sells-form">

    <?php $form = ActiveForm::begin(); 

    $persons = Persons::find()->all();
    $person = ArrayHelper::map($persons,'id','second_name');
    $params = [
    'prompt' => 'Выберите...'
    ];
    echo $form->field($model, 'user_id')->dropDownList($person,$params);


    $goods = Goods::find()->all();
    $good = ArrayHelper::map($goods,'id','name');
    $params = [
    'prompt' => 'Выберите...'
    ];
    echo $form->field($model, 'good_id')->dropDownList($good,$params);

    ?>


    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'itogo')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
