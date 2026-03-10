<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Persons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="persons-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Persons', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <p>В резиденции</p>
    <table>
    
    <?php
	foreach ($dataProvider as $row){
		if($row->inside == 1):
		?>
        <tr><td><?= $row->name;?></td><td><?= $row->second_name;?></td><td><?= $row->middle_name;?></td><td><?= $row->getVisitsCount($row->id);?></td></tr>
		<?php
		endif;
		}
	?>
	</table>
    <p>Нет</p>
    <table>
    
    <?php
	foreach ($dataProvider as $row){
		if($row->inside == 0):
		?>
        <tr><td><?= $row->name;?></td><td><?= $row->second_name;?></td><td><?= $row->middle_name;?></td><td><?= $row->getVisitsCount($row->id);?></td></tr>
		<?php
		endif;
		}
	?>
	</table>

</div>
