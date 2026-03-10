<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Vitalhit;

/* @var $this yii\web\View */
/* @var $model app\models\EventFinance */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Финансы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-finance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    

    <?php foreach ( $model->orders as $order ) {
        
        echo $order->id.' <a href="/crm/order/view?id='.$order->id.'">'.$order->title.'</a><br>'; 
        
        } ?>
    <p>
        <br>&nbsp;
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'event_id',
            'task_id',
            'from_user_id',
            'to_user_id',
            'from_contragent',
            'to_contragent',
            'date',
            'summa',
            'info:ntext',
            [                      // the owner name of the model
            'label' => 'Ссылка на документы',
            'format' => 'html',
            'value' => '<a href="'.$model->link.'">'.$model->link.'</a>',
        ],
        ],
    ]) ?>

</div>
