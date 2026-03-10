<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use mdm\admin\components\Helper;
use app\components\NotiWidget;
use app\components\FeedWidget;
use app\components\TaskforcheckWidget;
use app\components\TaskformeWidget;
use app\components\BookingWidget;
use app\components\CompanyWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!-- layouts/kudago.php -->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:image" content="https://igoevent.com/images/igoevent.jpg"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="/css/site.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
</head>
<body>
<?php $this->beginBody()?>

    <?php if (Yii::$app->session->hasFlash('success')) {
        echo "<div class='alert alert-success alert-dismissable'>
            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
            <h4> Готово! </h4>
            <p>".Yii::$app->session->getFlash('success')."</p>
        </div>";
    } elseif (Yii::$app->session->hasFlash('danger')) {
        echo "<div class='alert alert-danger alert-dismissable'>
            <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
            <h4> Внимание! </h4>
            <p>".Yii::$app->session->getFlash('danger')."</p>
        </div>";
    }?>
    
    <div class="up_btn"></div>
    <div class="wrap">
        <div class="container">
            <?= $content ?>
        </div>
    </div>

   
   
    <div class="overlay"></div>

    <?php $this->endBody() ?>
    <script src="/js/main.js"></script>
    <script src="/js/stacktable.js"></script>
</body>
</html>
<?php $this->endPage() ?>