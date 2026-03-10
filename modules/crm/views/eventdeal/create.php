<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Quote */

$this->title = 'Create Eventdeal';
$this->params['breadcrumbs'][] = ['label' => 'Eventdeal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
    <?= $this->render('/module/block_finance_report_eventdeal.twig', 
		[	'eventdeal_profit' => $eventdeal_profit, 'eventdeal_tickets' => $eventdeal_tickets]) ?>

</div>
