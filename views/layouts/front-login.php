<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Cities;
use app\models\Companies;
use app\models\Persons;
use mdm\admin\components\Helper;
use app\components\TaskforcheckWidget;
use app\components\TaskformeWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!-- layouts/front-login.php -->
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="og:image" content="https://igoevent.com/images/igoevent.jpg"/>
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<link href="/css/front.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="/js/front.js"></script>
	<meta name="google-site-verification" content="hvvAN4GLQLqNcOX0N-rbQSPKbnOlrRT0CaG42s4h5Qg" />
</head>
<body>

	<?php $this->beginBody()?>
	<?php $cities = Cities::Active()?>
	<?php $companies = Companies::Front()?>
	<header class="home" role="navigation">
		<div class="container">
			
			<div class="js_comp">
				<a href="/" class="logo "><img src="/images/logo.svg" alt="" title=""></a>
				<?php if($companies) {?>
					<ul class="comp-row">
						<?php foreach ($companies as $comp) {
							echo '<li class="city"><a href="/crm/company/about?id='. $comp->id .'">'. $comp->name .'</a></li>';
						}?>
					</ul>
				<?php }?>
			</div>

			<div class="geolocation">
				<h2>Ваш город <small class="geo-this">Москва?</small></h2>
				<ul class="geo-row">
					<?php foreach ($cities as $city) {
						echo '<li class="city"><a href="'. $city->alias .'">'. $city->name .'</a></li>';
					}?>
				</ul>
			</div>

			<div class="user-block">
			<?php if (Yii::$app->user->isGuest) {
				echo '<a href="/signup" class="btn btn-red user-reg">Регистрация</a>';
				//echo '<a href="/login" class="user-ident">Вход</a>';
				echo '<a class="user-ident jslogin">Вход</a>';
			} else {
				//echo '<a href="/profile" class="u-profile">' . Persons::Name() . ' </a>';
				echo '<a href="/profile">Билеты</a>&nbsp;&nbsp;';
				echo '<a href="/crm/company/my">Организаторам</a>';
				echo '&nbsp;&nbsp;<a href="/site/logout">Выход</a>';
			}; ?>

			</div>
		</div>
	</header>

	<?= $content ?>
<!-- <footer class="footer">
		<div class="bg_lgrey">
			<div class="container">
				<div class="col-xs-6 col-lg-2 m0"><p>&copy; iGoEvent <?= date('Y') ?></p></div>
				<div class="col-xs-6 col-lg-3 pull-right">Сайт сделал <a href="http://vitalhit.com/">Хитров Виталий Глебович</a>
				</div>
				<div class="col-xs-12 col-lg-1 tac">
					<!-- counter -->
<!--				</div>
			</div>
		</div>
	</footer> -->

	<?php $this->endBody() ?>
	<script src="/js/main.js"></script>
	<script src="/js/stacktable.js"></script>
	<noindex>
		<script type="text/javascript">document.write("<a rel='nofollow' href='//www.liveinternet.ru/click' "+"target=_blank><img src='//counter.yadro.ru/hit?t26.1;r"+escape(document.referrer)+((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+";h"+escape(document.title.substring(0,150))+";"+Math.random()+"' alt='' title='LiveInternet: показано число посетителей за"+" сегодня' "+"border='0' width='88' height='15'><\/a>");
		</script>
	</noindex>

	<!-- Yandex.Metrika counter --> 
	<script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(41253789, "init", { id:41253789, clickmap:true, trackLinks:true, accurateTrackBounce:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/41253789" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-88249632-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-88249632-1');
</script>

</body>
</html>
<?php $this->endPage() ?>
