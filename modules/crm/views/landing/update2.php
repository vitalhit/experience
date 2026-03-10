<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Landing */

$this->title = 'Обновить лендинг: ' . $model->title;
?>
<div class="landing-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form2', ['model' => $model]) ?>
</div>
