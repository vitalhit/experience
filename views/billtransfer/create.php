<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Billtransfer */

$this->title = 'Create Billtransfer';
$this->params['breadcrumbs'][] = ['label' => 'Billtransfers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billtransfer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
