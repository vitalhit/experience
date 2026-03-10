<?php

use yii\helpers\Html;

$this->title = 'обновить контрАгента: ' . $model->name;
?>

<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Обновить контрАгента</h2>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<div class="steps">
		<?= $this->render('/module/finance_step.twig') ?>
		</div>
	</div>

	<div class="col-xs-12 col-lg-9 newblos">
		<?= $this->render('_form', ['model' => $model, 'company' => $company ]) ?>
	</div>
</div>

