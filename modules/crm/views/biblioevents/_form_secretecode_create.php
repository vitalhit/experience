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
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'code')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'percent')->textInput() ?>
				</div>
				
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'biblioevent_id')->hiddenInput(['value' => $biblioevent_id])->label(false) ?>
				</div>
				<!--
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'event_id')->textInput() ?>
				</div>
				-->
				<div class="col-xs-12 col-lg-9">
					<?= $form->field($model, 'title')->textInput(['value' => ''])->label('Сообщение(автоматически создастся, если будет пустым)') ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'discount')->hiddenInput(['value' => '0'])->label(false); ?>
				</div>
				<!--
				<div class="col-xs-12 col-lg-12">
					<?= $form->field($model, 'info')->textarea(['rows' => '3']) ?>
				</div>
				-->
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
