<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Quote */

$this->title = 'Create PostBiblioevent';
$this->params['breadcrumbs'][] = ['label' => 'Post', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_join_to_biblioevent', [
        'model' => $model,
    ]) ?>

</div>
