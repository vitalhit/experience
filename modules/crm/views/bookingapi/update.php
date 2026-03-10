<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bookingapi */

$this->title = 'Update Bookingapi: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Все заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Редактировать заявку от '.$model->name.' '.$model->second_name];
?>
<div class="bookingapi-update">
    <?= $this->render('_form', [
        'model' => $model, 'lists' => $lists
    ]) ?>

</div>
