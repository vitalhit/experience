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


				<div class="col-xs-12 col-lg-7">
					<?= $form->field($model, 'title')->textInput() ?>
				</div>
				
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'item_id')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'item')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'usecase')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-12"><?= $form->field($model, 'html')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'preset' => 'full',
                    'inline' => false,
                ]]
         	    )->label();?>
         		</div>

				<dic class="clear"></dic>

				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'text')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'serv_info')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'attach')->textarea(['rows' => '3']) ?>
				</div>
				
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'tag')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'link')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'link_vk')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'link_fb')->textInput() ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'link_vk_cc')->textInput() ?>
				</div>
				
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_photos')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_videos')->textarea(['rows' => '3']) ?>
				</div>
				<div class="col-xs-12 col-lg-6">
					<?= $form->field($model, 'link_audio')->textarea(['rows' => '3']) ?>
				</div> 
				<div class="col-xs-12 col-lg-6">
				<?php  echo $form->field($model, 'published')->dropDownList([
		            '1' => 'Опубликован | 1',
		            '0' => 'Черновик | 0',
		            '-1' => 'Не опубликован | -1',
		        	]);?>
    			</div>
    			<div class="col-xs-12 col-lg-6">
				<?php  echo $form->field($model, 'deleted')->dropDownList([
		            '' => 'Не удален',
		            '1' => 'Удален | 1',
		            
		        	]);?>
    			</div>
				
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
