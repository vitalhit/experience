<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Categoryevents */

?>

<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Создайте категорию</h2>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<?= $this->render('/module/event_step.twig') ?>
	</div>

	<div class="col-xs-12 col-lg-9">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
</div>





