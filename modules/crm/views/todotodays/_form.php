<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\TasksStatus;
use app\models\TasksType;
use app\models\Users;
use app\models\Persons;
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


				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'title')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'link')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'status')->dropDownList([
						'0' => 'На паузу',
						'1' => 'Делать',
						'100' => 'Сделано',
						'-1' => 'Не делать',
						
					]);?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<label class="control-label" for="events-date">Дата</label>
					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_last',
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
				<div class="col-xs-12 col-lg-12">
					<?= $form->field($model, 'info')->textarea(['row' => '3']);?>
				</div>

				
				<div class="col-xs-12 col-lg-2">
					<?= $form->field($model, 'task_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-2">
					<?= $form->field($model, 'event_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-2">
					<?= $form->field($model, 'user_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-2">
					<?= $form->field($model, 'biblioevent_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-2">
					<?= $form->field($model, 'band_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'weekday')->dropDownList([
						'' => 'не выбран(пустой)',
						'1' => 'Понедельник',
						'2' => 'Вторник',
						'3' => 'Среда',
						'4' => 'Четверг',
						'5' => 'Пятница',
						'6' => 'Суббота',
						'7' => 'Воскресенье',
						
					]);?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'weekday_first')->dropDownList([
						'0' => 'первый день недели(0)',
						'1' => 'Понедельник',
						'2' => 'Вторник',
						'3' => 'Среда',
						'4' => 'Четверг',
						'5' => 'Пятница',
						'6' => 'Суббота',
						'7' => 'Воскресенье',
						
					]);?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'weekday_last')->dropDownList([
						'8' => 'последний день недели(8)',
						'1' => 'Понедельник',
						'2' => 'Вторник',
						'3' => 'Среда',
						'4' => 'Четверг',
						'5' => 'Пятница',
						'6' => 'Суббота',
						'7' => 'Воскресенье',
						
					]);?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'monthday')->dropDownList([
						'' => 'не выбран(пустой)',
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'1' => '8',
						'2' => '9',
						'3' => '10',
						'4' => '11',
						'5' => '12',
						'6' => '13',
						'7' => '14',
						'1' => '15',
						'2' => '16',
						'3' => '17',
						'4' => '18',
						'5' => '19',
						'6' => '20',
						'7' => '21',
						'1' => '22',
						'2' => '23',
						'3' => '24',
						'4' => '25',
						'5' => '26',
						'6' => '27',
						'7' => '28',
						'7' => '29',
						'7' => '30',
						'7' => '31',
					]);?>
					
				</div>
				
				
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right ml20']) ?>
		
		<input type="submit" name="goto" class="btn btn-success pull-right ml20" value="Сохранить и к списку">
		
	</div>

	<?php ActiveForm::end(); ?>

</div>
