<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Newsmakers */

$this->title = 'Обновить Eventdeal: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'All Eventdeal contacts', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];//
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clients-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
    <?= $this->render('/module/block_finance_report_eventdeal.twig', 
		[	'eventdeal_profit' => $eventdeal_profit, 'eventdeal_tickets' => $eventdeal_tickets]) ?>

</div>
