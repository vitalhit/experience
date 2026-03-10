<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use mdm\admin\components\Helper;
use app\components\TaskforcheckWidget;
use app\components\TaskformeWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!-- layouts/site.php -->
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <meta property="og:image" content="https://igoevent.com/images/igoevent.jpg"/> -->
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<link href="/css/front.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
	<?php $this->beginBody()?>
	<?php if (Yii::$app->session->hasFlash('success')) {
    echo "<div class='alert alert-success alert-dismissable'>
        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
        <h4> Готово! </h4>
        <p>" . Yii::$app->session->getFlash('success') . "</p>
        </div>";
} elseif (Yii::$app->session->hasFlash('danger')) {
    echo "<div class='alert alert-danger alert-dismissable'>
        <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
        <h4> Внимание! </h4>
        <p>" . Yii::$app->session->getFlash('danger') . "</p>
        </div>";
} ?>

	<?= $content ?>

	<?php $this->endBody() ?>
	<script src="/js/main.js"></script>
	<script src="/js/stacktable.js"></script>

	<!-- Yandex.Metrika counter --> 
	<script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(41253789, "init", { id:41253789, clickmap:true, trackLinks:true, accurateTrackBounce:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/41253789" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	
</body>
</html>
<?php $this->endPage() ?>
