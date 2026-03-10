<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Froms;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Persons */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>


<div class="row">
	<div class="col-xs-12 col-lg-3">
		<div class="blocks">
			<?php if ($model->image) { echo '<img src="/uploads/users/'.$model->image.'" class="img-responsive img-circle">';}
			echo $form->field($model, 'image')->fileInput(); ?>
		</div>
	</div>

	<div class="col-xs-12 col-lg-3">
		<div class="blocks">
			<?= $form->field($model, 'second_name') ?>
			<?= $form->field($model, 'name') ?>
			<?= $form->field($model, 'middle_name') ?>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3">
		<div class="blocks">
			<?= $form->field($model, 'phone') ?>
			<?= $form->field($model, 'mail') ?>
			<?= $form->field($model, 'city') ?>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3">
		<div class="blocks">
			<div class="row">
				<div class="col-xs-12 col-lg-7">
					<div class="form-group field-persons-birthday">
						<label class="control-label">День рождения</label>
						<?= DatePicker::widget([
							'model' => $model,
							'addon' => false,
							'attribute' => 'birthday',
							'language' => 'ru',
							'readonly' => false,
							'placeholder' => 'Выберите дату',
							'class' => 'form-control',
							'clientOptions' => [
								'format' => 'YYYY-MM-DD',
							],
						]);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'sex')->dropDownList([
						'0' => 'женский',
						'1' => 'мужской',
					]); ?>
				</div>
			</div>

			<?php $froms = Froms::find()->all(); 
			$items = ArrayHelper::map($froms,'id','url');
			$params = ['prompt' => 'Выберите...']; ?>

			<?= $form->field($model, 'vishes') ?>
			<?= $form->field($model, 'froms_id')->dropDownList($items,$params);?>
		</div>
	</div>
</div>

<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
