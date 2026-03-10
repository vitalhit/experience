<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;
use app\models\Biblioevents;
use app\models\Places;
use app\models\Seatings;
use app\models\Seats;
use mihaildev\ckeditor\CKEditor;


/* @var $this yii\web\View */
/* @var $model app\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-xs-12 col-lg-12">
			
			<div class="row">
				<h3>Добавить/редактировать дату:</h3>
				<div class="col-xs-6 col-lg-3">
					<span style="color:red;"><?= ($model->button)?$form->field($model, 'button')->textInput():$form->field($model, 'button')->textInput(['value'=> ($biblioevent->button_default?$biblioevent->button_default:'Купить билет')]); ?></span>
				</div>
				<div class="col-xs-6 col-lg-3">
					<span style="color:red;"><label class="control-label" for="events-date">Дата начала</label></span>
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
				<div class="col-xs-6 col-lg-3">	
					<?= $form->field($model, 'buttontextdate')->textInput(); ?>
				</div>
				<div class="col-xs-6 col-lg-3">	
					<?= $form->field($model, 'buttontext')->textInput(); ?>
				</div>
				
				<div class="col-xs-6 col-lg-3">	
				<?= $form->field($model, 'status')->dropDownList([
						'1' => 'Активна: отображается на странице',
						'0' => 'Скрытая: не отобржается на странице',
					]);?>

				</div>
			
				<div class="col-xs-6 col-lg-3">
					<label class="control-label" for="events-date">Открытие дверей(регистрации)</label>
					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_open',
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
			<label class="control-label" for="events-date">Дата окончания </label>
					<?= DatePicker::widget([
						'model' => $model,
						'attribute' => 'date_end',
						'language' => 'ru',
						'readonly' => false,
						'placeholder' => 'Выберите дату',
						'class' => 'form-control',
						'clientOptions' => [
							'format' => 'YYYY-MM-DD LT',
						'minDate' => '2021-04-01',
						//'maxDate' => '2015-09-10',
						],
					]);?>
				</div>	
				<div class="col-xs-6 col-lg-3"><!-- Надпись вместо даты -->
					<?= $form->field($model, 'underbutton')->textInput(); ?>
				</div>
				<div class="col-xs-6 col-lg-3">
				<?= $form->field($model, 'link_buy')->textInput(); ?>
				</div>
				
				
				<div class="col-xs-6 col-lg-3">
					<?= $form->field($model, 'formbutton')->textInput(); ?>
				</div>
				
				
				<div class="col-xs-6 col-lg-3">
					<?= $form->field($model, 'shema')->dropDownList([
							'0' => 'Нет',
							'1' => 'Отображать схему в форме',
							
						]);?>
				</div>
				<div class="col-xs-6 col-lg-3">
					<?= $form->field($model, 'soldout')->dropDownList([
							'0' => 'Билеты прдаются',
							'1' => 'Все билеты проданы',
							
						]);?>
				</div>
				<div class="col-xs-6 col-lg-3">
				<?= $form->field($model, 'cancel')->dropDownList([
							'' => 'Нет',
							'1' => 'Отмена',
							
						]);?>
				</div>
				<div class="col-xs-12 col-lg-3">
				<?= ($model->button)?$form->field($model, 'title')->textInput():$form->field($model, 'title')->textInput(['value'=> ($biblioevent->name?$biblioevent->name:'')]); ?>
				</div>
				
				<div class="col-xs-12 col-lg-3">
					<?= $form->field($model, 'event_id')->textInput(['value'=>  $biblioevent->id , 'readonly' => 'readonly']); ?>
					<?= $form->field($model, 'biblioevent_id')->textInput(['value'=>  $biblioevent->id , 'readonly' => 'readonly' , 'type'=> 'hidden'])->label(false); ?>
				</div>
				
				<div class="col-xs-6 col-lg-3">
				<?= $form->field($model, 'no_date')->dropDownList([
							'' => 'Отображать дату в билете',
							'1' => 'Скрыть дату в билете | 1',
						]);?>
				</div>
				<div class="col-xs-6 col-lg-3">
				<?= $form->field($model, 'link_gmap')->textInput(); ?>
				</div>
				<div class="col-xs-12 col-lg-3">
				<?= $form->field($model, 'status_promo')->dropDownList([
							'0' => '???',
							'1' => 'Участвует в партнерской пр-ме',
							'-1' => 'Не участвует в партнерской пр-ме',
						]);?>
				</div>
				<div class="col-xs-6 col-lg-3">	
					<?= $form->field($model, 'link_vk')->textInput(); ?>
				</div>

				<div class="col-xs-12 col-lg-3">
						<?php $places = Places::find()->where('city = '.$biblioevent->city)->all();
						$pl = ArrayHelper::map($places,'id','name','type');
						
						$trueplace = Null;
						if ( !$model->place_id) { $trueplace =  $biblioevent->place_id; }

						echo $form->field($model, 'place_id')->dropDownList($pl, ['options' =>[ $trueplace  => ['Selected' => true]]], ['prompt' => 'Выберите...']);
						?>
				</div>
				<div class="col-xs-6 col-lg-3">
					<br>
				<?= Html::submitButton('Сохранить и + билет <span class="glyphicon glyphicon-chevron-right"></span>',[ 'name'=>"new", 'value' =>'Сохранить и перейти на следующий шаг', 'class' => $model->isNewRecord ? 'btn btn-success pull-right ' : 'btn btn-primary pull-right ']) ?>
				</div>
			</div>	
		</div>
	</div> 
<?php ActiveForm::end(); ?>


<script type="text/javascript">
	$('.show_place').click(function(){
		$('.hidden_place').removeClass('hidden');
		$('.main_place').fadeOut();
	});
	$('.steps .date').addClass('active');
</script>