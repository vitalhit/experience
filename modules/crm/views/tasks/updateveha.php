<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['/projects/index']];
$this->params['breadcrumbs'][] = ['label' => 'Задача: '.$task->name, 'url' => ['/tasks/update?id='.$task->id]];
$this->params['breadcrumbs'][] = ['label' => 'Веха'];
?>


<?= $this->render('_form', [
	'model' => $model
]) ?>
