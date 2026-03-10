<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Return */

$this->title = 'Обновить возвраты: ' . $model->id;
?>

<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Финансовые операции</h2>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<?= $this->render('/module/return_step.twig') ?>
	</div>
	

	<div class="col-xs-12 col-lg-9 newblos">
		<?= $this->render('_form_update', ['model' => $model]) ?>
	</div>
</div>
