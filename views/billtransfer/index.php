<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Billtransfers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billtransfer-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Billtransfer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'bill_id_from',
            'bill_id_to',
            'bfrom',
            // 'bto',
            // 'info:ntext',
            // 'summa',
            // 'cat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
