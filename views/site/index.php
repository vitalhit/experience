<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */

$this->title = 'igoevent Я иду на событие!';
?>

<div class="container home-content">
<!-- 	<div class="city-row row">
		<?php foreach ($cities as $city) {
			echo '<div class="col-xs-6 col-lg-3"><a class="city" href="'. $city->alias .'"><img src="/images/city/'. $city->alias .'.jpg"><span>'. $city->name .'</span></a></div>';
		}?>
	</div> -->
	<div class="city-row row">
		<div class="col-xs-6 col-lg-3"><span class="city"><a href="msk"><img src="/images/city/msk.jpg"><span>Москва</span></a></span></div>
		<div class="col-xs-6 col-lg-3"><a class="city" href="spb"><img src="/images/city/spb.jpg"><span>Санкт-Петербург</span></a></div>
		
		<!--<div class="col-xs-6 col-lg-3"><a class="city" href="sochi"><img src="/images/city/sochi.jpg"><span>Сочи</span></a></div> -->
		
		<div class="col-xs-6 col-lg-3"><a class="city" href="onl"><img src="/images/city/onl.jpg"><span>Онлайн</span></a></div>
		<!--
		<div class="col-xs-6 col-lg-3"><a class="city" href="bali"><img src="/images/city/bali.jpg"><span>Бали</span></a></div>
		<div class="col-xs-6 col-lg-3"><a class="city" href="pushkin"><img src="/images/city/pushkin.jpg"><span>Пушкин</span></a></div>
		<div class="col-xs-6 col-lg-3"><a class="city" href="ptr"><img src="/images/city/ptr.jpeg"><span>Петрозаводск</span></a></div>
		<div class="col-xs-6 col-lg-3"><a class="city" href="travel"><img src="/images/city/travel.jpg"><span>Путешествия</span></a></div>-->
	</div>
	<h1 class="col-xs-12"></h1>
	<div class="city-row row">
		<div class="col-xs-6 col-lg-3 text-left">
		<b>Площадки</b><br>
		<a href="/msk/list/places">Москва</a><br>
		<a href="/spb/list/places">Санкт-Петербург</a>
		</div>
	</div>
	<h1 class="col-xs-12"><small>Сервис</small><br>Я иду на событие!</h1>
	<h2 class="col-xs-12">События наших партнеров<br><small>онлайн и оффлайн</small></h2>

	<div class="pro col-xs-6 col-sm-6 col-md-3 mh300">
		<a href="https://igoevent.com/band/vitalhit"><img class="img" width="270px" height="170px" src="/images/expert/2025-vitalhit-01-270x170.jpeg"></a>
		<h4><a href="https://igoevent.com/band/vitalhit">Виталий Хитров</a></h4>
		<p>«Создаю события!»</p>
	</div>
	
	<div class="pro col-xs-6 col-sm-6 col-md-3 mh300">
		<a href="https://igoevent.com/band/zhanmoro"><img class="img" width="270px" height="170px" src="/images/expert/2023-zhanmoro-01-270x170.jpeg"></a>
		<h4><a href="https://igoevent.com/band/zhanmoro">Мороз Жанна</a></h4>
		<p>«Научу общаться, а&nbsp;не&nbsp;говорить!»</p>
	</div>
	<div class="pro col-xs-6 col-sm-6 col-md-3 mh300">
		<a href="https://igoevent.com/band/toma"><img class="img" width="270px" height="170px" src="/images/expert/2025-tomastulova-01-270x170.jpeg"></a>
		<h4><a href="https://igoevent.com/band/toma">Toма Стулова</a></h4>
		<p>«Sex, Drive & Rock'n'Roll»</p>
	</div>
	<div class="pro col-xs-6 col-sm-6 col-md-3 mh300">
		<a href="https://igoevent.com/band/sofya"><img class="img" width="270px" height="170px" src="/images/expert/2025-sofya-01-270x170.jpeg"></a>
		<h4><a href="https://igoevent.com/band/sofya">Софья Кудрова</a></h4>
		<p>Керамистка на всю голову</p>
	</div>
	<div class="pro col-xs-6 col-sm-6 col-md-3 mh300">
		<a href="https://igoevent.com/band/harplivemusic"><img class="img" width="270px" height="170px" src="/images/expert/2023-harplivemusic-01-270x170.jpeg"></a>
		<h4><a href="https://igoevent.com/band/harplivemusic">Ольга Максимова</a></h4>
		<p>Композитор, арфистка</p>
	</div>

	<!--
	<div class="pro col-xs-6 col-sm-6 col-md-3 mh300">
		<a href="https://igoevent.com/band/sitnivok"><img class="img" width="270px" height="170px" src="/images/expert/2023-sitnivok-01-270x170.jpeg"></a>
		<h4><a href="https://igoevent.com/band/sitnivok">Виктория&nbsp;Геннадиевна Ситник</a></h4>
		<p>Мода и&nbsp;стиль</p>
	</div>
	-->
	
	
	

	<h2 class="col-xs-12">Функционал<br><small>онлайн и оффлайн событий</small></h2>
	<ul class="function-row">
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-agenda-yellow.svg"><h4>Создавай события и даты</h4></li>
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-voronka-yellow.svg"><h4>Используй воронки продаж</h4></li>
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-users-yellow.svg"><h4>Собирай базу клиентов</h4></li>
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-promocode-yellow.svg"><h4>Используй промо-коды</h4></li>
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-site-yellow.svg"><h4>Создавай лендинги</h4></li>
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-dollars-yellow.svg"><h4>Продавай билеты</h4></li>
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-plaine-yellow.svg"><h4>Отправляй рассылки</h4></li>
		<li class="col-xs-6 col-sm-4 col-md-3"><img src="/images/ico-ringer-yellow.svg"><h4>Ставь задачи</h4></li>
	</ul>
-->
</div>
<div class="container open-form">
	<div class="col-xs-12">
		<a name="reg"></a>
		<h2>Регистрируйся<br><small>и получи <span>бесплатно</span> полный доступ к сервису</small></h2>

		<a href="/signup" class="btn btn-red user-reg ma fln w200">Регистрация</a>

		<!-- <?= Html::errorSummary($model)?>
		<?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'form-static-page']); ?>
		<?= $form->field($model, 'username') ?>
		<?= $form->field($model, 'email') ?>
		<?= $form->field($model, 'password')->passwordInput() ?>
		<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-red', 'name' => 'signup-button']) ?>
		<?php ActiveForm::end(); ?> -->

	</div>
</div>

<footer class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8">
			<img class="logo-footer" src="/images/logo-mini.svg">
			<p class="copyrite">© 2018-<?php echo date("Y"); ?> iGoEvent.com — мы создаем события. Права на текстовые и другие материалы, размещенные на сайте, охраняются законом.
			При цитировании обязательна прямая ссылка на iGoEvent.com.</p>
			<ul class="footer-nav">
				
				<li><a href="page/about">О проекте</a></li>
				<li><a href="page/contacts">Контакты</a></li>
				<li class="years"><img src="/images/ico-18-plus.svg"></li>
			</ul>
		</div>
	</div>

</footer>


<!-- 	<div class="col-xs-12 col-md-3 main_right">
		<a class="btn btn-primary m20a pull-right" href="/login">Вход</a>

		<div class="site-signup">
			<h2>Зарегистрироваться</h2>
			
		</div>
	</div> -->

