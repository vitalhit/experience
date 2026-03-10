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
					<?= $form->field($model, 'title')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-12">
					<?= $form->field($model, 'info')->textarea(['rows' => 3])  ?>	
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_site')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_vk')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_fb')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_insta')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_tg')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'public_vk_id')->textInput() ?>
				</div>
			</div>

			
		</div>

		<div class="col-xs-12 col-lg-4">
			<div class="row">
				<div class="col-xs-12 col-lg-10">                                    
					<?= $form->field($model, 'city_id')->textInput() ?>
				</div>
				<?php if(!empty($info)) {?>
					<div class="col-xs-12 col-lg-2">
						<a href="/crm/events/update?id=<?php echo $model->info ?>"><span class="glyphicon form_glyphicon c_blue glyphicon-calendar"></span></a>
					</div>
				<?php } ?>  
				




				<div class="col-xs-12 col-lg-10">
					<label class="control-label" for="events-date">Дата добавления</label>

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
			</div>
		</div>
		
	</div>
	<?= $form->field($model, 'serv_info')->textarea(['rows' => 3])  ?>	

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
