<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Secretcode */

?>
<div class="clients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_secretecode_create', [
        'model' => $model , 'biblioevent_id' => $biblioevent_id
    ]) ?>

</div>
