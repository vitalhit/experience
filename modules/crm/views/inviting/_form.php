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
					<?= $form->field($model, 'event_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'event_vk_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'public_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'public_vk_id')->textInput() ?>
				</div>
				
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'info')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<div class="row">
						<div class="col-xs-12 col-lg-3">
						<?= $form->field($model, 'result_all')->textInput() ?>
						</div>
					
						<div class="col-xs-12 col-lg-3">
						<?= $form->field($model, 'result_invite')->textInput() ?>
						</div>
					
						<div class="col-xs-12 col-lg-3">
						<?= $form->field($model, 'result_ignore')->textInput() ?>
						</div>

						<div class="col-xs-12 col-lg-3">
						<?= $form->field($model, 'result_ban')->textInput() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
