<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tickets */
/* @var $form yii\widgets\ActiveForm */
?>


	<?php $form = ActiveForm::begin(); ?>

<div class="row">
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'event_id')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'seat_id')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-3">
		<?= $form->field($model, 'money')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'count')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-3">
		<?= $form->field($model, 'summa')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-4">
		<?= $form->field($model, 'from_url')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-3">
		<?= $form->field($model, 'type')->dropDownList([
				'1' => 'Наличный(1)',
				'2' => 'Безналичный(2)',
				'3' => 'Билетник(3)',
				'9' => 'Билетник(9)',
			]);?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'user_id')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-3">
		<?= $form->field($model, 'status')->dropDownList([
				'1' => 'Бронь(1)',
				'7' => 'Возврат(7)',
				'8' => 'Отмена(8)',
				'5' => 'Оплачен(5)',
				'0' => 'Удален(0)',
			]);?>
	</div>

	<div class="col-xs-12 col-md-4">
		<?= $form->field($model, 'info')->textInput(['placeholder' => 'info']) ?>
	</div>

	<div class="col-xs-12 col-md-4">
		<?= $form->field($model, 'admin')->textInput(['placeholder' => 'admin']) ?>
	</div>
	<div class="col-xs-12 col-md-4">
		<?= $form->field($model, 'mark')->textInput(['placeholder' => 'пометка администратора']) ?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'send')->dropDownList([
				'0' => 'Не отправлено | 0',
				'1' => 'Отправлено | 1',
				'3' => 'Кликнул | 3'
			]);?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'seat')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'client_id')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'order_id')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'date')->textInput() ?>
	</div>
	<div class="col-xs-12 col-md-2">
		
		<?= $form->field($model, 'canceled')->dropDownList([
				'' => 'Не аннулирован',
				'1' => 'Аннулирован',
			]);?>
	</div>
	<div class="col-xs-12 col-md-2">
		<?= $form->field($model, 'template_id')->textInput() ?>
	</div>
</div>
	
	

	<div class="form-group">
		<?php if(!$model->isNewRecord){ echo'<input type="submit" name="new" class="btn btn-info" value="Save and go to list tickets">';} ?>


		<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['name'=>'new' , 'class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
	
	</div>
	<?php ActiveForm::end(); ?>



	


<script type="text/javascript">

	$(function(){
		$('#tickets-money, #tickets-count').bind("change keyup", function() {
			var result = $("#tickets-money").val() * $("#tickets-count").val();
			$("#tickets-summa").val(result);
		});
	})

</script>