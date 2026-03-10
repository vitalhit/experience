<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BookList */

$this->title = 'Создать лист';
?>
<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Листы ожидания</h2>            
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<?= $this->render('/module/person_step.twig', ['model' => $model]) ?>
	</div>

	<div class="col-xs-12 col-lg-9">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
</div>
