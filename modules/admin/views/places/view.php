<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Places */

?>
    <h1><?= Html::encode($model->name) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'city',
            'metro',
            'phone',
            'address',
            'path:ntext',
            'description:ntext',
            'yandex',
            'map',
            'foto',
        ],
    ]) ?>
