<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Froms;

/* @var $this yii\web\View */
/* @var $model app\models\Persons */
/* @var $form yii\widgets\ActiveForm */
?>

	<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'company_id')->hiddenInput(['value' => Yii::$app->user->identity->company_active])->label(false) ?>

	
	<div class="row">
		<div class="col-xs-12 col-lg-3">
			<?php if ($model->image) { echo '<img src="/uploads/users/'.$model->image.'" class="img-responsive img-circle">';}
			echo $form->field($model, 'image')->fileInput(); ?>
		</div>

		<div class="col-xs-12 col-lg-3">
			<?= $form->field($model, 'mail') ?>
			<?= $form->field($model, 'second_name') ?>
			<?= $form->field($model, 'name') ?>
			<?= $form->field($model, 'middle_name') ?>
			<?= $form->field($model, 'city') ?>
			<?= $form->field($model, 'birthday')->textInput(['placeholder' => "1980-05-28"]) ?>
		</div>
		<div class="col-xs-12 col-lg-3">
			<?= $form->field($model, 'phone') ?>

			<?= $form->field($model, 'groups') ?>
			<?= $form->field($model, 'vishes') ?>
			<?= $form->field($model, 'sex')->dropDownList([
				'' => 'не выбран',
				
				'1' => 'мужской',
				'0' => 'женский',
			]); ?>
			<?= $form->field($model, 'sendmail')->dropDownList([
				'0' => 'Нет',
				'1' => 'Да'
			]);?>
			<?= $form->field($model, 'status') ?>
		</div>
		<div class="col-xs-12 col-lg-3">
			<?php $froms = Froms::find()->all(); 
			$items = ArrayHelper::map($froms,'id','url');
			$params = ['prompt' => 'Выберите...']; ?>
			<?= $form->field($model, 'froms_id')->dropDownList($items,$params);?>
			
			<?= $form->field($model, 'discount') ?>
			<?= $form->field($model, 'info')->textarea(['rows' => '6']) ?>
			<?= $form->field($model, 'vk_id')->textInput() ?>
			<?= $form->field($model, 'fb_id')->textInput() ?>
			<?= $form->field($model, 'company_id')->textInput() ?>
		</div>
	</div>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>
