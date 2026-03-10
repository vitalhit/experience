<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;
use app\models\Persons;


/* @var $this yii\web\View */
/* @var $model app\models\Abonements */

$this->title = 'Создать абонемент';
?>
<div class="abonements-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
		]) ?>

</div>
