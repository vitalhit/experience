<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['index']];
?>

<div class="row">
	<div class="col-xs-12 col-sm-10">
		<h1>Проект: <?= Html::encode($this->title) ?></h1>
	</div>
	<div class="col-xs-12 col-sm-2">
		<a href="/tasks/create?projectid=<?php echo $model->id ?>" class="btn btn-primary pull-right mt20">+ Создать задачу</a>
	</div>

	<div class="col-xs-12">

		<a class="btn btn-xs btn-info" href="/projects/update?id=<?php echo $model->id ?>">Вce</a>

		<?php foreach ($statuses as $st) {
			echo '<a class="btn btn-xs btn-info" href="/projects/update?id='.$model->id.'&status='.$st->id.'">'.$st->name.'</a>';
		} ?>

		<div class="clearfix mt20"></div>

		<table class="table stacktable">
			<tr>
				<td>Начало</td>
				<td>Дедлайн</td>
				<td>Время</td>
				<td>Прогресс</td>
				<td>Название</td>
				<td>Ответственный</td>
				<td>Cтатус</td>
				<td>Приоритет</td>
				<td>Качество</td>
				<td>Результат</td>
				<!--<td>Перенос</td-->
				<td>url</td>
				<td></td>
			</tr>
			<?php foreach ($tasks as $task) {
				if ($task->status_id == 4) {
					echo '<tr class="green">';
				}elseif ($task->status_id == 2) {
					echo '<tr class="yellow">';
				}elseif ($task->status_id == 3) {
					echo '<tr class="red">';
				}elseif ($task->status_id == 5) {
					echo '<tr class="blue">';
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
				
				<td>';
				if ($task->steps) { // прогресс для задачи по шагам
					$all = 0;
					$work = 0;
					$done = 0;
					$progress = 0;

					foreach ($task->steps as $step) {
						if ( $step->status_id == 4 ) {
							$done++;
						} elseif ( $step->status_id == 2 ) {
							$work++;
						}
						$all++;
					}
						
					if ($done != 0) {
						$progressdone = $done/$all*100;
					}
						
					if ($work != 0) {
						$progresswork = $work/$all*100;
					}
					if ($all != 0) {
						echo '<div class="progress">
									<div class="progress-bar progress-bar-success" role="progressbar" style="width:'.$progressdone.'%;"></div>
									<div class="progress-bar progress-bar-warning" role="progressbar" style="width:'.$progresswork.'%;"></div>
								</div>';
					};
				}
				echo'</td>

				<td><a href="/tasks/update?id='.$task->id.'&projectid='.$model->id.'">'.$task->name.'</a></td>';
				if ( $task->owner_id ) {
					echo '<td>'.$task->owners->username.'</td>';
				}
				echo '<td>'.$task->status->name.'</td>
				<td class="tac"><div class="priority priority'.$task->priority.'">'.$task->priority.'</div></td>
				<td>'.$task->quality.'</td>
				<td>'.$task->result.'</td>
				<!--td>'.$task->delay.'</td-->
				<td>';
					if ( $task->url ) {
						echo '<a href="'.$task->url.'"> ссылка </a>';
					};
					echo '</td>
				<!--td><a class="deltask" id="'.$task->id.'"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></a></td-->
					<td><a href="/tasks/delete?taskid='.$task->id.'&projectid='.$model->id.'" title="Отвязать" aria-label="Отвязать" data-confirm="Уверены в том, что хотите удалить?" data-method="post"><span class="glyphicon glyphicon-remove"></span></a></td>
				</tr>';
			}?>
		</table>
      
<p>Ссылки на компании, которым принадлежит этот проект</p>
	</div>
</div>

<div class="clearfix"></div>

<div class="mt20">
	<?= $this->render('_form', ['model' => $model, 'companies' => $companies]) ?>
</div>






// <script type="text/javascript">
	
// 	$(function(){

// 		$(".deltask").click(function(){
// 			var taskid = $(this).attr('id');
// 			var projectid = $(this).attr('id');			
// 			$.ajax({
// 				type: 'get',
// 				url: "/tasks/deletetask",
// 				data: {'taskid': taskid, 'projectid': projectid},
// 				response: 'text',
// 				success: function(data){
// 					$(this).parent().parent('tr').fadeOut();
// 				}
// 			})
// 			console.log(taskid, projectid);
// 		})
// 	})

// </script>