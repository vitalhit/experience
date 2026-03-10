<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Landings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="landing-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Landing', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'anons',
            'text:ntext',
            'video1',
            //'video2',
            //'video3',
            //'videotitle',
            //'imagetitle',
            //'image1',
            //'image1title',
            //'image1link',
            //'image1author',
            //'image2title',
            //'image2link',
            //'image2author',
            //'image2',
            //'image3title',
            //'image3link',
            //'image3author',
            //'image3',
            //'image4title',
            //'image4link',
            //'image4author',
            //'image4',
            //'image5title',
            //'image5link',
            //'image5author',
            //'image5',
            //'image6title',
            //'image6link',
            //'image6author',
            //'image6',
            //'image7title',
            //'image7link',
            //'image7author',
            //'image7',
            //'image8',
            //'image8title',
            //'image8link',
            //'image8author',
            //'image9',
            //'image9title',
            //'image9link',
            //'image9author',
            //'image10',
            //'image10title',
            //'image10link',
            //'image10author',
            //'kudago',
            //'site',
            //'vk',
            //'fb',
            //'you',
            //'insta',
            //'phone',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
