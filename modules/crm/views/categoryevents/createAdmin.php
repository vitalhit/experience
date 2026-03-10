<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Categoryevents */

?>

<h1>Создайте категорию</h1>

<div class="row">
	<div class="col-xs-12 col-lg-9">
		<?= $this->render('_form', [
			'model' => $model, 'companies' => $companies
		]) ?>
	</div>
	<div class="col-xs-12 col-lg-3 r_info">
		<p>Как правило категорию создавать не нужно, уже есть категория для вашего события. Если это так, переходите к следующему шагу</p>
	</div>
</div>