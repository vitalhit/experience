<?php

use yii\helpers\Html;


$this->title = 'Обновить промокод: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'All secretcodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];//
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clients-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_secretecode_update', [
        'model' => $model,
    ]) ?>

</div>
