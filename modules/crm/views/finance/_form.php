<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\TasksStatus;
use app\models\TasksType;
use app\models\Users;
use app\models\Events;
use app\models\Smena;
use app\models\Employees;
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
				<div class="col-xs-12 col-lg-12">
					<?= $form->field($model, 'name')->textInput() ?>
				</div>
				<div class="col-xs-6 col-lg-3">
					<?= $form->field($model, 'summa')->textInput() ?>
				</div>
				<div class="col-xs-6 col-lg-2">
					<?= $form->field($model, 'summa_k')->textInput(['placeholder'=>'00 коп.']) ?>
				</div>
				<div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-6 col-lg-5">
					<label class="control-label" for="events-date">Дата перевода</label>

					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_payment',
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
					<?= $form->field($model, 'state')->dropDownList([
						'1' => 'на подпись',
						'2' => 'подписана',
						'3' => 'проведена',
						'-1' => 'не актуальна'
					]) ?>
				</div>
				<div class="col-xs-6 col-lg-3">
					<?= $form->field($model, 'status')->dropDownList([
						'1' => 'Приход',
						'2' => 'Конвертация',
						'3' => 'Расход'
					]) ?>
				</div>
				<div class="col-xs-6 col-lg-3">
					<?= $form->field($model, 'info_pay_bank')->textInput() ?>
				</div>
				<div class="col-xs-6 col-lg-3">
					<?= $form->field($model, 'info_pay_machine')->textInput() ?>
				</div>

				

				
				
				

				<div class="col-xs-12 col-lg-12">
					<div class="mini_header mt0"><span>Договор</span></div>
				</div>
				

				<div class="col-xs-12 col-lg-5">
					<?php $contragents = Contragent::Map();
					echo $form->field($model, 'from_contragent')->dropDownList($contragents, ['prompt' => 'Выберите ...']);?>
				</div>
				<div class="col-xs-12 col-lg-1">
					<span class="glyphicon form_glyphicon glyphicon-share-alt"></span>
				</div>
				<div class="col-xs-12 col-lg-5">
					<?php echo $form->field($model, 'to_contragent')->dropDownList($contragents, ['prompt' => 'Выберите ...']);?>
				</div>
				<div class="col-xs-12 col-lg-1">
					<a href="/crm/contragent/create"><span class="glyphicon form_glyphicon_big glyphicon-plus-sign"></span></a>
				</div>


				<div class="col-xs-12 col-lg-12">
					<div class="mini_header mt0"><span>Договор</span></div>
				</div>
				


				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'doc_number')->textInput() ?>
				</div><div class="col-xs-12 col-lg-1"></div>
				
				<div class="col-xs-12 col-lg-5">
					<label class="control-label" for="events-date">Дата договора</label>

					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_doc',
						'language' => 'ru',
						'readonly' => false,
						'placeholder' => 'Выберите дату',
						'class' => 'form-control',
						'clientOptions' => [
							'format' => 'YYYY-MM-DD',
                        //'minDate' => '2015-08-10',
                        //'maxDate' => '2015-09-10',
						],
					]);?>
				</div><div class="col-xs-12 col-lg-1"></div>

				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'bill_name')->textInput() ?>
				</div><div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-12 col-lg-5">
					<label class="control-label" for="events-date">Дата счета</label>

					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date',
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

			<div class="row">
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'act_number')->textInput() ?>
				</div><div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-12 col-lg-5">
					<label class="control-label" for="events-date">Дата акта</label>

					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_act',
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
				</div><div class="col-xs-12 col-lg-1"></div>	

				

			</div>
			<div class="row">
				<div class="col-xs-12 col-lg-12">
					<div class="mini_header mt0"><span>Логистика</span></div>
				</div>
				<div class="col-xs-12 col-lg-10">
					<?= $form->field($model, 'status_logistics')->dropDownList([
						'' => 'Не нужна',
						'1' => 'Требуется',
						'100' => 'Сделана',
					]) ?>
				</div>
				
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'logistics')->dropDownList([
						'0' => 'не требуется',
						'1' => 'требуется',
						'2' => 'отправлен на согласование',
						'3' => 'подписан',
						'4' => 'отправлен скан',
						'5' => 'у курьера',
						'6' => 'доставлен',
						'7' => 'завершено'
						
					]) ?>
				</div>
				<div class="col-xs-12 col-lg-1">
					<span class="glyphicon form_glyphicon glyphicon-share-alt"></span>
				</div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'logistics_our')->dropDownList([
						'0' => 'не требуется',
						'1' => 'требуется',
						'2' => 'отправлен на согласование',
						'3' => 'подписан',
						'4' => 'отправлен скан',
						'5' => 'у курьера',
						'6' => 'доставлен',
						'7' => 'наша копия получена'
					]) ?>
				</div>
				
			</div>
		</div>

		<div class="col-xs-12 col-lg-4">
			<div class="row">

				<?php if(!empty($event_id)) {?>
					<div class="col-xs-12 col-lg-2">
						<a href="/crm/events/update?id=<?php echo $model->event_id ?>"><span class="glyphicon form_glyphicon c_blue glyphicon-calendar"></span></a>
					</div>
				<?php } ?> 


				<div class="col-xs-6 col-lg-6">                                    
					<?php
					  if (empty($biblioevent_id)) {
					  	echo $form->field($model, 'event_id')->textInput();
					  } else{
						echo $form->field($model, 'event_id')->dropDownList(ArrayHelper::map(Events::find()->where(['event_id' => $biblioevent_id])->andwhere('DATE(date) > DATE(NOW())')->orderBy(['date'=>SORT_ASC])->all(), 'id', 'date'), ['prompt' => 'Выберите ...'])	;
						}
					 ?>

				</div>
				<div class="col-xs-6 col-lg-6">
					<?php 
						
							echo $form->field($model, 'smena_id')
							->dropDownList(ArrayHelper::map(Smena::find()->orderBy(['id'=>SORT_DESC])->all(), 'id', 'title'), ['prompt' => 'Выберите ...', 'value' => ($model->smena_id?$model->smena_id:'')]);
						
					?>
				</div>
				<div class="col-xs-12 col-lg-10">                                    
					<?= $form->field($model, 'link')->textInput() ?>
				</div> 
				
				<div class="col-xs-6 col-lg-6">
				<?= $form->field($model, 'employee_id')->dropDownList(ArrayHelper::map(Employees::find()->where(['company_id'=>$model->company_id])->orWhere('status=1')->all(), 'id', 'second_name'), ['prompt' => 'Выберите ...']) ?>
					
				</div>
				<div class="col-xs-6 col-lg-6">
					<a target="_blank" href="https://igoevent.com/crm/employees/" class="btn btn-success btn-xs">employees</a>
				</div>

				<?php if($model->task_id) {?>
					<div class="col-xs-12 col-lg-2">
						<a href="/crm/tasks/update?id=<?php echo $model->task_id ?>"><span class="glyphicon form_glyphicon c_blue glyphicon-th-list"></span></a>
					</div>
				<?php } ?>

				
				
				
				

				
				<div class="col-xs-12 col-lg-10">
					<?= $form->field($model, 'task_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-10">
					<?= $form->field($model, 'project_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-10">
					<?= $form->field($model, 'client_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-10">
					<?= $form->field($model, 'contract_template_id')->textInput() ?>
				</div> 
				<div class="col-xs-12 col-lg-10">                                    
					<?= $form->field($model, 'company_id')->textInput(['value' => Companies::getCompanyId()])->label("id company — не менять!"); ?>
				</div> 
				<?php //echo $form->field($model, 'company_id')->hiddenInput(['value' => Companies::getCompanyId()])->label(false); ?>
			</div>
		</div>
		<div  class="col-xs-12 col-lg-8">
			
		</div>
	</div>

	<div class="col-xs-12 col-lg-12">
					<div class="mini_header mt0"><span>Дополнительная информация</span></div>
	</div>


	<?= $form->field($model, 'info')->textarea(['rows' => 3]) ?>
	<div class="row">
		<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'from_user_id')->textInput() ?>
				</div><div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'to_user_id')->textInput() ?>
				</div><div class="col-xs-12 col-lg-1"></div>
	</div>

	<?= $form->field($model, 'info_reason')->textarea(['rows' => 3]) ?>
	<?= $form->field($model, 'info_pay')->textarea(['rows' => 1]) ?>
	<?= $form->field($model, 'info_doc')->textarea(['rows' => 1]) ?>
	<?= $form->field($model, 'serv_info')->textarea(['rows' => 3]) ?>

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
