<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\TasksStatus;
use app\models\TasksType;
use app\models\TaskReport;
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
		
				<div class="col-xs-3 col-lg-3">                                    
					<?= $form->field($model, 'event_id')->textInput() ?>
				</div>
				<div class="col-xs-3 col-lg-3">                                    
					<?= $form->field($model, 'task_id')->textInput() ?>
				</div>
				<div class="col-xs-3 col-lg-3">                                    
					<?= $form->field($model, 'user_id')->textInput() ?>
				</div>
				<div class="col-xs-3 col-lg-3">                                    
					<?= $form->field($model, 'client_id')->textInput() ?>
				</div>
				<div class="col-xs-3 col-lg-3">                                    
					<?= $form->field($model, 'minutes')->textInput() ?>
				</div>
				<div class="col-xs-3 col-lg-3">                                    
					<?= $form->field($model, 'date')->textInput(['readonly' => readonly	]) ?>
				</div>
				<?php if(!empty($event_id)) {?>
					<div class="col-xs-12 col-lg-2">
						<a href="/crm/events/update?id=<?php echo $model->event_id ?>"><span class="glyphicon form_glyphicon c_blue glyphicon-calendar"></span></a>
					</div>
				<?php } ?>  
	</div>
	<?= $form->field($model, 'info')->textarea(['rows' => 3 ])  ?>	
	<?= $form->field($model, 'info_public')->textarea(['rows' => 3 ])  ?>	

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
