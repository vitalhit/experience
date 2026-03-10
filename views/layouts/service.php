<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Biblioevents;
use app\models\Cities;
use app\models\Persons;
use mdm\admin\components\Helper;
use app\components\TaskforcheckWidget;
use app\components\TaskformeWidget;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!-- layouts/service.php -->
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<link href="/css/front.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="/js/front.js"></script>
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
	<?php $cities = Cities::Active()?>
	<?php $bib = Biblioevents::EventOwner(Yii::$app->request->get('alias'), Yii::$app->request->get('city'));?> 


	<header class="navbar" role="navigation">
		<div class="container">
			<a href="/" class="logo"><img src="/images/logo.svg" alt="" title=""></a>

			<div style="line-height: 80px;">
				<?php if (Yii::$app->user->isGuest) {
					//echo '<a href="/#reg" class="btn btn-red user-reg">Регистрация</a>';
					echo '<a href="/login" class="user-ident">Вход</a>';
				} else {
					if (!empty($bib)){
						echo '<a style="margin: 0 4px 0 0px;" href="/crm/biblioevents/view?id='.$bib->id.'" class="u-profile"><span class="glyphicon glyphicon-user"></span></a><span>&nbsp;</span><a href="https://igoevent.com/crm/events/events?biblioeventid='.$bib->id.'" class="u-profile"><span class="glyphicon glyphicon-calendar"></span></a><span>&nbsp;</span><a href="https://igoevent.com/crm/biblioevents/update?id='.$bib->id.'" class="u-profile"><span class="glyphicon glyphicon-pencil"></span></a><span>&nbsp;</span>';

						
						if ($bib->band_id){
							echo '<a href="https://igoevent.com//crm/band/view?id='.$bib->band_id.'"><span class="glyphicon glyphicon-music"></span></a><span>&nbsp;</span>';
						}
					}
					echo '<a style="margin: 0 4px 0 4px;" href="/profile" class="u-profile">Мои билеты</a>';
					echo '&nbsp;&nbsp;<a href="/site/logout"> Выход</a>';
				}; ?>
			</div> 
			
			
		</div>

	</header>


	<div class="container title-section">
	<div class="row">		
		<?= $content ?>
		</div>
	</div>

	<?php $this->endBody() ?>
	<script src="/js/main.js"></script>
	<script src="/js/stacktable.js"></script>

	<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(41253789, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/41253789" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>
<?php $this->endPage() ?>
