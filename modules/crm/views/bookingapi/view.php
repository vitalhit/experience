<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bookingapi */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bookingapis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookingapi-view">

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
            'from_url:url',
            'name',
            'second_name',
            'thirdname',
            'owner_id',
            'status_id',
            'vk_id',
            'fb_id',
            'in_id',
            'foto',
            'birthday',
            'mail',
            'phone',
            'message:ntext',
            'sex',
            'sale',
            'time',
            'close_time',
            'role',
            'result',
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_content',
            'utm_term',
            'info:ntext',
        ],
    ]) ?>

</div>
