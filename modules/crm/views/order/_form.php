<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\Users;
use app\models\CompanyUser;
use app\models\Companies;
use app\models\Contragent;

/* @var $this yii\web\View */
/* @var $model app\models\EventFinance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-finance-form">

	<?php $form = ActiveForm::begin(); ?>

	<?php echo $form->field($model, 'company_id')->hiddenInput(['value' => Companies::getCompanyId()])->label(false); ?>

	<div class="row">
		<div class="col-xs-12 col-lg-8">
			<div class="row">
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'title')->textInput() ?>
				</div>
				<div class="col-xs-6 col-lg-3">
					<span style="color:red;"><label class="control-label" for="events-date">Дата создания</label></span>
					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_create',
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
				<div class="col-xs-6 col-lg-3">
					<span style="color:red;"><label class="control-label" for="events-date">Дата старта</label></span>
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
				<div class="col-xs-6 col-lg-3">
					<span style="color:red;"><label class="control-label" for="events-date">Дата завершения</label></span>
					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_end',
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
				<div class="col-xs-6 col-lg-3">
					<span style="color:red;"><label class="control-label" for="events-date">Дата факт. выполнения</label></span>
					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_complete',
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
					<?= $form->field($model, 'price')->textInput() ?> 
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'closed')->dropDownList([
						
						'0' => 'Открыт',
						'1' => 'Закрыт',
					]) ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					
					<?= $form->field($model, 'deleted')->dropDownList([
						'' => 'не удален',
						'1' => 'удален',
					]) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'task')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'info')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'info_draft')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'info_client')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'info_report')->textarea(['rows' => '3']) ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
