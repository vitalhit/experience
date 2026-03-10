<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;
use app\models\Seatings;
use app\models\Seats;

/* @var $this yii\web\View */
/* @var $model app\models\Seats */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seats-form">

	<?php $form = ActiveForm::begin(); ?>


	<div class="row mt30">
		<div class="col-xs-12 col-lg-3">
			<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Билет/регеистрация']) ?>
		</div>
		<div class="col-xs-12 col-lg-3">
			<div class="row">
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'count')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'price')->textInput() ?>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6">
			<div class="row">
				<div class="col-xs-12 col-lg-8">
				<?= $form->field($model, 'info')->textInput();?>
				</div>
				<div class="col-xs-12 col-lg-4">
						<?= $form->field($model, 'promocode')->textInput(['placeholder' => 'igoevent123']) ?>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-3 flr">
			<div class="btn btn-primary more_field mt25 flr">Доп. поля</div>
		</div>
		<div class="clear"></div>

		<div class="hidd" >
			<div class="col-xs-12 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-lg-4">
						<?= $form->field($model, 'sec')->textInput() ?>
					</div>
					<div class="col-xs-12 col-lg-2">
						<?= $form->field($model, 'row')->textInput() ?>
					</div>
					<div class="col-xs-12 col-lg-3">
						<?= $form->field($model, 'nums')->textInput() ?>
					</div>
					<div class="col-xs-12 col-lg-3">
						<?= $form->field($model, 'css')->textInput() ?>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-lg-6">
				<div class="row">
					
					
					<div class="col-xs-12 col-lg-3">
						<?= $form->field($model, 'promolimit')->textInput() ?>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-lg-6">
				<?php echo $form->field($model, 'afterpay')->dropDownList([
					'1' => 'Можно',
					'0' => 'Нельзя'
				]);?>
			</div>
			<div class="col-xs-12 col-lg-6">
				<?php echo $form->field($model, 'template_id')->dropDownList([
					'' => 'Без шаблона',
					'1' => '15kop: сертификат на 1го | Мск',
					'2' => '15kop: сертификат на 2их | Мск',
					'3' => 'Предварительная регистрация(3)',
					'4' => 'Мамтеатр: подарочный сертификат',
					'5' => '15kop: сертификат на 1го | СПб ',
					'6' => '15kop: сертификат на 2их | СПб ',
					'7' => 'Регистрация(7)',
					'8' => 'Регистрация без имени и стоимости(8)',
					'9' => 'Мастер-классы маркета 4 Сезона(9)'
				]);?>
			</div>
			<div class="col-xs-12 col-lg-3">
				<span style="color:red;"><label class="control-label" for="seats-date_start">Начало продаж</label></span>
						<?= DatePicker::widget([
							'model' => $model,
							'attribute' => 'date_start',
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
			<div class="col-xs-12 col-lg-3">
				<span style="color:red;"><label class="control-label" for="seats-date_stop">конец продаж</label></span>
						<?= DatePicker::widget([
							'model' => $model,
							'attribute' => 'date_stop',
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
			<div class="col-xs-12 col-lg-3">
				
						<?php echo $form->field($model, 'type')->dropDownList([
					'1' => 'обычный',
					'2' => 'регистрация: нельзя выбирать количество ',
					
				]);?>
			</div>
		</div>
	</div>

	<div class="form-group clear">
		<input type="submit" name="new" class="btn btn-info btm-sm" value="Сохранить и создать еще тип билета">
		
		<!--
		<?= Html::submitButton('Сохранить и перейти на следующий шаг <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?> -->
	</div>

	<?php ActiveForm::end(); ?>
</div>

<script>
	more_field
</script>



<script type="text/javascript">
	$(document).ready(function() {
		$('.more_field').click(function() {
			$('.hidd').toggle();
		});
	});
</script>