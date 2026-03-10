<?php

use yii\helpers\Html;


$this->title = 'Обновить inviting: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'All invitings', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];//
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clients-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
