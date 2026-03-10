<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Clients */

$this->title = 'Create Promoter';
$this->params['breadcrumbs'][] = ['label' => 'Promoters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
