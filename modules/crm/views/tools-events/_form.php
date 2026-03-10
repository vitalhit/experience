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
				<div class="col-xs-12 col-lg-12">
					<?= $form->field($model, 'event_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'tool_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-12 col-lg-5">
					<?= $form->field($model, 'status')->textInput() ?>
				</div><div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-12 col-lg-5">
				<?php echo $form->field($model, 'info')->textarea(['rows' => 5]); ?> 
				</div>
				<div class="col-xs-12 col-lg-1"></div>
				<div class="col-xs-4 col-lg-3">
				<?php echo $form->field($model, 'date_create')->textInput(['readonly' => 'readonly']); ?> 
				</div>
				<div class="col-xs-4 col-lg-2">
				<?php echo $form->field($model, 'user_id')->textInput(['readonly' => 'readonly']); ?> 
				</div>
				
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
