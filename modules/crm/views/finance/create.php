<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EventFinance */

$this->title = 'Создать финансы';
?>
<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Финансовые операции</h2>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<div class="steps">
		<?= $this->render('/module/finance_step.twig') ?>
		</div>
	</div>

	<div class="col-xs-12 col-lg-9 newblos">
		<div class="steps">
		<?= $this->render('_form_create', ['model' => $model, 'smena'=>$smena]) ?>
		</div>
	</div>
</div>
