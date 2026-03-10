<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Newsmakers */

$this->title = 'Create Newsmakers';
$this->params['breadcrumbs'][] = ['label' => 'Newsmakers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
