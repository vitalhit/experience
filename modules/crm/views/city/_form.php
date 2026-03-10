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
use mihaildev\ckeditor\CKEditor;

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
					<?= $form->field($model, 'name')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'alias')->textInput() ?>
				</div>
			</div>
			<div class="row">
				 <div class="mini_header mt0"><span>Инфо</span></div>
    		<?= $form->field($model, 'info')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ]]
    )->label(false);?>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
