<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TaskReport */

$this->title = 'Создать отчет';
?>
<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Отчет</h2>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<?= $this->render('/module/report_step.twig') ?>
	</div>

	<div class="col-xs-12 col-lg-9 newblos">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
</div>
