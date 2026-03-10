<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Seatings */

?>

<div class="module1 steps">
	<div class="step step6"><a href="/places/create/" target="_blank">Создайте площадку</a></div>
	<div class="step step6 active"><a href="/seatings/create/" target="_blank">Создайте рассадку</a></div>
	<div class="step step6"><a href="/seats/create/" target="_blank">Создайте типы билетов</a></div>
	<div class="step step6"><a href="/categoryevents/create/" target="_blank">Создайте категорию</a></div>
	<div class="step step6"><a href="/biblioevents/create/" target="_blank">Создайте событие</a></div>
	<div class="step step6"><a href="/events/create/" target="_blank">Создайте дату</a></div>
</div>

<h1>Создайте рассадку</h1>

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