<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Buys */

$this->title = 'Create Buys';
$this->params['breadcrumbs'][] = ['label' => 'Buys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="buys-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
