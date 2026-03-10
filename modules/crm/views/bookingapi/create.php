<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bookingapi */

?>
<div class="bookingapi-create">

    <h1>Создать заявку</h1>

    <?= $this->render('_form', [
        'model' => $model, 'lists' => $lists??Null
    ]) ?>

</div>
