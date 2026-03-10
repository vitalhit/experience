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


				<div class="col-xs-12 col-lg-7">
					<?= $form->field($model, 'name')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'biblioevent_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'event_id')->textInput() ?>
				</div>
				
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'link')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'vk')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'fb')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'insta')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'tg')->textInput() ?>
				</div>
				
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'yt')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'tw')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'tool_id')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'media_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'newsmaker_id')->textInput() ?>
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
