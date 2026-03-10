<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Contest */

$this->title = 'Create Contests';
$this->params['breadcrumbs'][] = ['label' => 'Contests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
