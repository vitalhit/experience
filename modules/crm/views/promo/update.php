<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Newsmakers */

$this->title = 'Обновление статуса Newsmakers_Events: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'All promo', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];//
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clients-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?php if ($gourl) {echo  '<script>setTimeout( \'location="'.$gourl.'";\', 1 );</script>';}
 	?>
</div>
