<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Seatings */

$this->title = 'Рассадка: ' . $model->name;
?>
    
<div class="module1 steps">
	<div class="step step6"><span>Создайте площадку</span></div>
	<div class="step step6 active"><span>Создайте рассадку</span></div>
	<div class="step step6"><span>Создайте типы билетов</span></div>
	<div class="step step6"><span>Создайте категорию</span></div>
	<div class="step step6"><span>Создайте событие</span></div>
	<div class="step step6"><span>Создайте дату</span></div>
</div>


<h1><?= Html::encode($this->title) ?></h1>

<div class="row">
	<div class="col-xs-12 col-lg-9">
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
	<div class="col-xs-12 col-lg-3 r_info">
		<p>Если в списке мест нет вашего, <a href="/places/create/" class="btn btn-primary" target="_blank"> + создайте место</a></p>
		<p>Название рассадки - только для вас, чтобы вы отличали когда какие билеты продаете.</p>
		<p>Загрузите свой рисунок "Схемы зала" - она нужна, чтобы гость мог выбрать зону или ряд мест и соориентироваться в вашем зале. Она не обязательна.</p>
	</div>
</div>
