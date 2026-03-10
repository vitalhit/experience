<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ToolsEvents */

$this->title = 'Create ToolsEvents';
$this->params['breadcrumbs'][] = ['label' => 'ToolsEvents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
