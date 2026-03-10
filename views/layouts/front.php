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
<!-- layouts/front.php -->
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


			<div class="user-block">
			<?php if (Yii::$app->user->isGuest) {
				echo '<a href="/signup" class="btn btn-red user-reg">Регистрация</a>';
				echo '<a href="/login" class="user-ident">Вход</a>';
				//echo '<a class="user-ident jslogin">Вход</a>';
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
				<div class="col-xs-6 col-lg-3 pull-right"><p>Сайт сделал <a href="http://vitalhit.com/">Хитров Виталий Глебович</a></p>
				</div>
				<div class="col-xs-12 col-lg-1 tac">
					
				</div>
			</div>
		</div>
	</footer> -->

	<?php $this->endBody() ?>
	<script src="/js/main.js"></script>
	<script src="/js/stacktable.js"></script>
	<noindex>
		<!-- liveinternet -->
	</noindex>

	<!-- Yandex.Metrika counter --> 

</body>
</html>
<?php $this->endPage() ?>
