<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Smena */

$this->title = 'Обновить фестиваль ' . $model->id;
?>

<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Обновить фестиваль</h2>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<?= $this->render('/module/festival_step.twig') ?>
	</div>
	

	<div class="col-xs-12 col-lg-9 newblos">
		<?= $this->render('_form_update', ['model' => $model]) ?>
	</div>
</div>
