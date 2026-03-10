<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Froms;

/* @var $this yii\web\View */
/* @var $model app\models\Persons */

$this->title = 'Добавить персональную карточку гостя';
$this->params['breadcrumbs'][] = ['label' => 'Persons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="persons-update">

	<div class="new_form">
		<h1 class="tac"><?= Html::encode($this->title) ?></h1>
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
</div>




<script type="text/javascript">
	
$(document).ready(function(){

	//Наличие такого юзера в системе по mail
	$(document).on('change', '#persons-mail', function() {
		var mail = $('#persons-mail').val();
		$.ajax({
			url: '/crm/persons/personisset?mail='+mail,
			dataType: 'json',
			success: function(data){
				if (data){
					$('.new_form').html('<h3 class="tac">Такой гость уже есть!</h3><a href="/persons/view?id='+data.id+'" class="tac db">'+data.second_name+' '+data.name+'</a><br><a class="btn btn-success ma w200" href="/persons/viewbegin?id='+data.id+'">Начать визит</a>');
				}
			}
		})
	});
});

</script>