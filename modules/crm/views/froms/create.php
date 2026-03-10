<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Froms */

$this->title = 'Create Froms';
$this->params['breadcrumbs'][] = ['label' => 'Froms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="froms-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
