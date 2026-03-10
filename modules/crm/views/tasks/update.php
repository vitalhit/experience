<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['/projects/index']];
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
?>



<div class="row">
	<div class="col-xs-12 col-md-6 col-lg-6">
		<h2 class="mt0 tal">Задача из проекта <?= $model->projects->name;?></h2>
	</div>

	<div class="col-xs-12 col-md-6 col-lg-6">
		<h3 class="tal">Сделать подзадачей для</h3>
		<form method="post" id="addtotask">
			<input type="hidden" name="taskid" value="<?= $model->id ?>">
			<div class="col-xs-12 col-md-6">
				<input type="text" name="parentid" class="form-control" value="" placeholder="Введите id родительской задачи">
			</div>
			<div class="col-xs-12 col-md-6">
				<input type="submit" class="form-control btn-primary" value="сохранить">
			</div>
		</form>
	</div>
</div>

<hr>

<?php // Подтаски ?>

<div class="row">
	<div class="col-xs-12 col-sm-10">
		<h2 class="mt0">Шаги (подзадачи)</h2>
	</div>
	<div class="col-xs-12 col-sm-2">
		<a href="/tasks/create?tasksid=<?php echo $model->id ?>" class="btn btn-primary pull-right mt20">+ Создать шаг</a>
	</div>
</div>

<a class="btn btn-xs btn-info" href="/tasks/update?id=<?php echo $model->id ?>">Вce</a>

<?php foreach ($statuses as $st) {
	echo '<a class="btn btn-xs btn-info" href="/tasks/update?id='.$model->id.'&status='.$st->id.'">'.$st->name.'</a>';
} ?>

	<div class="clearfix mt20"></div>

	<table class="table stacktable">
		<tr>
			<td>Начало</td>
			<td>Дедлайн</td>
			<td>Время</td>
			<td>Название</td>
			<td>Ответственный</td>
			<td>Cтатус</td>
			<td>Приоритет</td>
			<td>Качество</td>
			<td>Результат</td>
				<!--<td>Перенос</td>
				<td>url</td>-->
				<td></td>
			</tr>
			<?php foreach ($tasks as $task) {
				if ($task->status_id == 4) {
					echo '<tr class="green">';
				}elseif ($task->status_id == 2) {
					echo '<tr class="yellow">';
				}elseif ($task->status_id == 3) {
					echo '<tr class="red">';
				}else{
					echo '<tr>';
				}
				echo '<td>'.date('d.m.Y H:i', strtotime($task->start)).'</td>
				<td>';
					if ($task->end) {
						echo date('d.m.Y H:i', strtotime($task->end));
					} else {
						echo " - ";
					}
					echo '</td>
				<td>';
					if ($task->time) {
						echo date('H:i', strtotime($task->time));
					} else {
						echo " - ";
					}
					echo '</td>
				<td><a href="/tasks/updateveha?id='.$task->id.'">'.$task->name.'</a></td>';
					if ( $task->owner_id ) {
						echo '<td>'.$task->owners->username.'</td>';
					}
					echo '<td>'.$task->status->name.'</td>
				<td class="tac"><div class="priority priority'.$task->priority.'">'.$task->priority.'</div></td>
				<td>'.$task->quality.'</td>
				<td>'.$task->result.'</td>
				<!--td>'.$task->delay.'</td>
				<td>';
					if ( $task->url ) {
						echo '<a href="'.$task->url.'"> ссылка </a>';
					};
					echo '</td-->
					<!--td><a class="deltask" id="'.$task->id.'"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></a></td-->
				<td><a href="/tasks/deletestep?id='.$task->id.'&parent_id='.$model->id.'" title="Удалить" aria-label="Удалить" data-confirm="Уверены в том, что хотите удалить?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
			</tr>';
		}?>
	</table>

<hr>

<?= $this->render('_form', [
	'model' => $model, 'allprojects' => $allprojects
]) ?>



<script type="text/javascript">

	$("#addtoproject").submit(function(e) {
		var forma = $("#addtoproject").serialize();
		$.ajax({
			type: "POST",
			url: "/tasks/addtoproject",
			data: forma,
			success: function(data){
				$('.projects').html(data);
			}
		});
		e.preventDefault();
		console.log(data);
	}
	);


	$("#addtotask").submit(function(e) {
		var forma = $("#addtotask").serialize();
		$.ajax({
			type: "POST",
			url: "/tasks/addtotask",
			data: forma,
			success: function(data){
				alert(data);
			}
		});
		e.preventDefault();
		console.log(data);
	});

</script>
