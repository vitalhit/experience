<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use nex\datepicker\DatePicker;
use app\models\TasksStatus;
use app\models\TasksType;
use app\models\Todos;
use app\models\Users;
use app\models\Persons;
use app\models\CompanyUser;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
	<div class="col-lg-5">
		<div class="mini_header mt0"><span>ЗАДАЧА</span></div>
		<?= $form->field($model, 'name')->textarea(['rows' => 4]) ?>

		<?= $form->field($model, 'info')->textarea(['rows' => 4]) ?>

		<?= $form->field($model, 'result')->textarea(['rows' => 4]) ?>
	</div>

	<div class="col-md-7">
		<div class="mini_header mt0"><span>НАСТРОЙКИ</span></div>
		<div class="row">


			<?php $tasksstatus = TasksStatus::find()->all(); 
			$items_stat = ArrayHelper::map($tasksstatus,'id','name');?>

			<div class="col-xs-6 col-lg-3">
				<?= $form->field($model, 'status_id')->dropDownList($items_stat) ?>
			</div>

			<div class="col-xs-6 col-lg-3">
				<?= $form->field($model, 'priority')->dropDownList([
					'11' => 'нет',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10'
				]);?>
			</div>

			<div class="col-xs-6 col-lg-2">
				<?= $form->field($model, 'prev')->textInput() ?>
			</div>
			<div class="col-xs-6 col-lg-2">
				<?= $form->field($model, 'next')->textInput() ?>
			</div>


			<div class="clear"></div>

			<?php
			$user = Users::findOne(Yii::$app->user->id);
			$user_ids = CompanyUser::find()->select('user_id')->where(['company_id' => $user->company_active])->asArray()->all();
			$items_user = Users::Map($user_ids);

			// echo "<pre>";
			// print_r($items_user);
			// echo "</pre>";
			?>

			<div class="col-xs-6 col-lg-5">
				<?php if ($model->isNewRecord) {
					$param = ['options' =>[ Yii::$app->user->id => ['Selected' => true]]]; //Выбираем создателя по умолчанию по ID 
					echo $form->field($model, 'owner_id')->dropDownList($items_user, $param);
				} else{
					echo $form->field($model, 'owner_id')->dropDownList($items_user);
				} ?>
			</div>

			<div class="col-xs-6 col-lg-5">
				<?php if ($model->isNewRecord) {
					$param = ['options' =>[ Yii::$app->user->id => ['Selected' => true]]]; //Выбираем создателя по умолчанию по ID 
					echo $form->field($model, 'creator_id')->dropDownList($items_user, $param);
				} else{
					echo $form->field($model, 'creator_id')->dropDownList($items_user);
				} ?>
			</div>
			<div class="hidden-xs col-lg-2">
				<?php if (!empty($parent_task)) {
					echo $form->field($model, 'parent_id')->textInput(['value' => $parent_task->id]);
				}else{
					echo $form->field($model, 'parent_id')->textInput();
				} ?>
			</div>

			<div class="hidden-xs col-lg-6">
				<?php if (!empty($projects)) {
					
						echo $form->field($model, 'project_id')->hiddenInput(['value' => $projects['id']])->label(false);
					/*if (count($projects) == 1) {

					} else {
						$pro = ArrayHelper::map($projects,'id','name');
						echo $form->field($model, 'project_id')->dropDownList($pro);
					}*/

					?>
				<?php } ?>
			</div>

			<div class="col-xs-6 col-lg-5">
				<?= $form->field($model, 'url')->textInput() ?>
			</div>
			<div class="col-xs-6 col-lg-5">
				<?= $form->field($model, 'todo_id')->dropDownList(ArrayHelper::map(Todos::find()->all(), 'id', 'title'), ['prompt' => 'Выберите ...']) ?>		
			</div>
			<div class="col-xs-12 col-lg-1 mt20">
				<?php if ($model->url) { 
				echo '<a href="'.$model->url.'"><span class="normal glyphicon glyphicon-link"></span></a>';
				} ?>
			</div>

			<div class="clear"></div>


			<div class="col-xs-6 col-lg-4">
				<label class="control-label">Начало</label>
				<?= DatePicker::widget([
					'model' => $model,
					'addon' => false,
					'attribute' => 'start',
					'language' => 'ru',
					'readonly' => false,
					'placeholder' => 'Выберите дату',
					'class' => 'form-control',
					'clientOptions' => [
						'format' => 'YYYY-MM-DD LT',
					],
				]);
				?>
			</div>

			<div class="col-xs-6 col-lg-4">
				<label class="control-label">Дедлайн</label>
				<?= DatePicker::widget([
					'model' => $model,
					'addon' => false,
					'attribute' => 'end',
					'language' => 'ru',
					'readonly' => false,
					'placeholder' => 'Выберите дату',
					'class' => 'form-control',
					'clientOptions' => [
						'format' => 'YYYY-MM-DD LT',
					],
				]);
				?>
			</div>

			<div class="hidden-xs col-lg-4">
				<label class="control-label">Заняло часов</label>
				<?= DatePicker::widget([
					'model' => $model,
					'addon' => false,
					'attribute' => 'time',
					'language' => 'ru',
					'readonly' => false,
					'placeholder' => 'Кол-во часов',
					'class' => 'form-control',
					'clientOptions' => [
						'format' => 'LT',
					],
				]);
				?>
			</div>


			<div class="clear"></div>
			
			<div class="col-xs-12 col-lg-4 mt20">
				 <?php if ($model->url) { 
				  echo '<a href="https://igoevent.com/crm/clients/update?id='.$model->client_id.'"><span class="normal glyphicon glyphicon-link"></span> Клинет № '.$model->client_id .'</a>';
				 } ?>
			</div>
			<div class="clear"></div>
			<h4 class="js_dops mt20">Дополнительно <span class="glyphicon glyphicon-resize-vertical"></span></h4>
			<div class="dops">
				<?php $taskstype = TasksType::find()->all(); 
				$items_type = ArrayHelper::map($taskstype,'id','name');?>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'type_id')->dropDownList($items_type) ?>
				</div>
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'delay')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'person_id')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'client_id')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'quality')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'dohod')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'rashod')->textInput() ?>
				</div>

				<div class="col-xs-12 col-lg-3">
					<label class="control-label">Итог</label>
					<input type="text" disabled="disabled" class="form-control" value="<?= $model->dohod - $model->rashod?>">
				</div>
			</div>

		</div>
	</div>
</div>

<div class="form-group">
	<input type="submit" name="new" class="btn btn-info" value="Сохранить и создать еще задачу">

	<?= Html::submitButton('Сохранить и продолжить <span class="glyphicon glyphicon-chevron-right"></span>', ['class' => 'btn btn-success pull-right', 'name' => 'new']) ?>
</div>
		<pre><?= $model->info ?></pre>
<?php ActiveForm::end(); ?>


<script type="text/javascript">
	$('.steps .tasks').addClass('active');
	
	$(".dops").hide();

	$( ".js_dops" ).click(function(){
		$(".dops").toggle();
	});

</script>