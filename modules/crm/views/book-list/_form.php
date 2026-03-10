<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Companies;

/* @var $this yii\web\View */
/* @var $model app\models\BookList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-list-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="col-xs-12 col-lg-8"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>

	<?php $comp_ids = Companies::getIds();
	$companies = Companies::find()->where(['id' => $comp_ids])->all();
	$items = ArrayHelper::map($companies,'id','brand');?>

	<div class="col-xs-12 col-lg-8"><?= $form->field($model, 'company_id')->dropDownList($items) ?></div>

	<div class="col-xs-12 col-lg-8"><?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?></div>

	<?php ActiveForm::end(); ?>

</div>
