<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventFinance */

$this->title = 'Обновить финансы: ' . $model->id;
?>

<div class="row">
	<div class="col-xs-12 col-lg-12">
		<div class="newblo">
			<h2>Logistics: Финансовые операции</h2>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 left_blo">
		<div class="steps">
		<?= $this->render('/module/finance_step.twig') ?>
		</div>
	</div>
	<div class="col-xs-12 col-lg-3 newblos">
		<a href="/crm/finance/akt?&id=<? echo $model->id;?>">скачать акт</a><br>
		<a href="/crm/finance/bill?&id=<? echo $model->id;?>">скачать счет</a><br>
		
	</div>
	<div class="col-xs-12 col-lg-3 newblos">
		<a href="/crm/finance/contractfoursiz2020?&id=<? echo $model->id;?>">скачать договор 4 сезона(2020)</a><br>
		<a href="/crm/finance/contractloan?&id=<? echo $model->id;?>">скачать договор займа</a><br>
		<a href="/crm/finance/makecorp?&id=<? echo $model->id;?>">скачать договор на корпоратив</a><br>
	</div>
	<div class="col-xs-12 col-lg-3 newblos">
		<small>Хитров Виталий: 5/<b>23047</b><br>
		Роман Жариков: 24630/<b>64964</b></small>

	</div>

	<div class="col-xs-12 col-lg-9 newblos">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
</div>
