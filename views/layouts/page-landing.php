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
<!-- layouts/page-landing.php -->
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<link href="/css/front.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
	<?php $this->beginBody()?>
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
			
			<!-- <form class="search" name="small-search" id="search-form" action="/search" method="get">
				<input name="q" type="text" class="search-query" data-content="#search-results" placeholder="События, люди, места ...">
				<button class="btn-search"></button>
			</form> -->
			
			<!-- <div class="geolocation">
				<a class="geo-this">Москва</a>
				<ul class="geo-row">
					<?php foreach ($cities as $city) {
						echo '<li class="city"><a href="'. $city->alias .'">'. $city->name .'</a></li>';
					}?>
				</ul>
			</div> -->


			<!-- <a id="nav-tgl" class="navbar-toggle">
				<div class="icon-nav"><span></span><span></span><span></span></div>
				Меню
			</a> -->
		</div>

<!-- 		<div id="nav" class="navbar-menu">
			<div class="container">
				<ul class="nav">
					<a href="/" class="logo-mini"></a>
					<li class="home"><a href="/"><img src="/images/logo-mini.svg" alt="" title=""></a></li>
					<li class=""><a href="/razdel">Выставки</a></li>
					<li><a href="/razdel">Концерты</a></li>
					<li><a href="/razdel">Театр</a></li>
					<li><a href="/razdel">Кино</a></li>
					<li><a href="/razdel">Фестиваль</a></li>
					<li><a href="/razdel">Экскурсии</a></li>
					<li><a href="/razdel">События</a></li>
					<li><a href="/razdel">Развлечения</a></li>
					<li class="nav-more">
						<a>Еще</a>
						<ul>
							<li><a href="/razdel">Дети</a></li>
							<li><a href="/razdel">Кино</a></li>
							<li><a href="/razdel">Ярмарки </a></li>
							<li><a href="/razdel">Рестораны/Бары</a></li>
							<li><a href="/razdel">Квесты</a></li>
							<li><a href="/razdel">Клубы</a></li>
							<li><a href="/razdel">Музеи</a></li>
						</ul>
					</li>
					<li class="dop-link"><a class="more-sale" href="/razdel">Скидки</a></li>
					<li class="dop-link"><a class="more-top" href="/razdel">Лучшее</a></li>
				</ul>
			</div>
		</div> -->
	</header>




	<?= $content ?>

	<?php $this->endBody() ?>
	<script src="/js/main.js"></script>
	<script src="/js/stacktable.js"></script>

	<!-- Yandex.Metrika counter -->

</body>
</html>
<?php $this->endPage() ?>
