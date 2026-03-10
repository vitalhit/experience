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

	

	<div class="row">
		<div class="col-xs-12 col-lg-8">
			<div class="row">
				<div class="col-xs-12 col-lg-12">
					<?= $form->field($model, 'text')->textInput(['readonly' => 'readonly']) ?>
				</div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'price')->textInput(['readonly' => 'readonly']) ?>
				</div><div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'owner')->textInput(['readonly' => 'readonly']) ?>
				</div><div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'date_create')->textInput(['readonly' => 'readonly']) ?>
				</div>
			</div>

			
		</div>

		<div class="col-xs-12 col-lg-4">
			<div class="row">
				<div class="col-xs-12 col-lg-10">                                    
					<?= $form->field($model, 'event_id')->textInput(['readonly' => 'readonly']) ?>
				</div>
				<?php if(!empty($event_id)) {?>
					<div class="col-xs-12 col-lg-2">
						<a href="/crm/events/update?id=<?php echo $model->event_id ?>"><span class="glyphicon form_glyphicon c_blue glyphicon-calendar"></span></a>
					</div>
				<?php } ?>  
				

				<div class="col-xs-12 col-lg-10">
					<?= $form->field($model, 'status')->dropDownList([
						'-1' => 'Отмена',
						'0' => 'Новая заявка',
						'1' => 'Процесс запущен',
						'3' => 'Возвращен(возврат оформлен в банке)	'
					]) ?>
				</div>



				<div class="col-xs-12 col-lg-10">
					<label class="control-label" for="events-date">Дата возврата</label>

					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_done',
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
			</div>
		</div>

	</div>
	<?= $form->field($model, 'conditions')->textarea(['rows' => 3 , 'readonly' => readonly	])  ?>	

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
