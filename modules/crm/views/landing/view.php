<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Landing */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Landings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="landing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'anons',
            'text:ntext',
            'video1',
            'video2',
            'video3',
            'videotitle',
            'imagetitle',
            'image1',
            'image1title',
            'image1link',
            'image1author',
            'image2title',
            'image2link',
            'image2author',
            'image2',
            'image3title',
            'image3link',
            'image3author',
            'image3',
            'image4title',
            'image4link',
            'image4author',
            'image4',
            'image5title',
            'image5link',
            'image5author',
            'image5',
            'image6title',
            'image6link',
            'image6author',
            'image6',
            'image7title',
            'image7link',
            'image7author',
            'image7',
            'image8',
            'image8title',
            'image8link',
            'image8author',
            'image9',
            'image9title',
            'image9link',
            'image9author',
            'image10',
            'image10title',
            'image10link',
            'image10author',
            'kudago',
            'site',
            'vk',
            'fb',
            'you',
            'insta',
            'phone',
        ],
    ]) ?>

</div>
