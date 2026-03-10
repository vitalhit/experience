<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

if ($task) {
	$this->title = 'Создать шаг';
}else{
	$this->title = 'Создать задачу';
}
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
	'model' => $model, 'allprojects' => $allprojects, 'task' => $task, 'projectid' => $projectid,
]) ?>

