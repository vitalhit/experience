<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EventFinance */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'test', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-finance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'event_id',
            'task_id',
            'from_user_id',
            'to_user_id',
            'from_contragent',
            'to_contragent',
            'date',
            'summa',
            'info:ntext',
        ],
    ]) ?>

</div>
