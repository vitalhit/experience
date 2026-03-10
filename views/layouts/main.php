<?php

use yii\helpers\Html;
use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use mdm\admin\components\Helper;
use app\components\TaskforcheckWidget;
use app\components\TaskformeWidget;
use app\components\TaskrememberWidget;
use app\components\CompanyWidget;
use app\components\BookingWidget;
use app\models\Companies;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<!-- layouts/main.php -->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta property="og:image" content="https://igoevent.com/images/igoevent.jpg"/> -->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="/web/js/jquery.min.js"></script>
    <link href="/css/site.css" rel="stylesheet">
</head>

<body>
<?php $this->beginBody() ?>

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

<div class="up_btn"></div>

<div class="wrap">
    <?php
    if (empty(Companies::getCompany())) {
        $menuItems = [
            ['label' => 'Компания', 'url' => ['/crm/company/about']],
            ['label' => 'Билеты', 'url' => ['/profile/index']],

        ];
    } else {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/']],
            ['label' => 'Кабинет', 'url' => ['/crm/company/about'], 'encode' => false],
            ['label' => 'Даты', 'url' => ['/crm/events/']],
            ['label' => 'События', 'url' => ['/crm/biblioevents/']],
            
            ['label' => '<span class="badge badge_die">' . BookingWidget::count() . '</span>',
                'encode' => false,
                'url' => ['/crm/bookingapi/index?status=1'],
                'visible' => BookingWidget::count(),
            ],
            ['label' => 'Билеты', 'url' => ['/profile/']],
        ];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/login/']];
        $menuItems[] = ['label' => 'Регистрация', 'url' => ['/signup/']];
    } else {
        $menuItems[] = ['label' => 'Выход', 'url' => ['/site/logout/']];
    };

    

    // тут подродно о меню http://www.webapplex.ru/vidzhet-menu-v-yii-frejmvorke-2.x

    NavBar::begin([
        'brandLabel' => CompanyWidget::widget().'<span class="ml10 fs14 glyphicon glyphicon-option-vertical"></span>',
        'brandUrl' => '/crm/company/my',
        'options' => [
            'class' => 'navbar navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => Helper::filter($menuItems),
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="bg_grey">
        <ul class="fmenu">
            <li></li>
            <!--<li><a href="https://igoevent.com/crm/newsmakers">Newsmakers</a></li>
            <li><a href="https://igoevent.com/crm/employees">Team</a></li>
            <li><a href="https://igoevent.com/crm/partners">Partners</a></li>
            <li><a href="https://igoevent.com/crm/bookingapi">Заявки</a></li>
            <li><a href="/crm/projects/my">Проекты</a></li>
            <li><a href="/crm/tasks/my">Задачи</a></li>
            <li><a href="/crm/tasks/my?status=1&status2=2&owner=<?= Yii::$app->user->id ?>">Мне</a></li>
            <li><a href="/crm/tasks/my?status=1&status2=2&creator=<?= Yii::$app->user->id ?>">Мои</a></li>
            <li><a href="/crm/events">Даты</a></li>
            <li><a href="https://igoevent.com/crm/tool">Tools</a></li>
            <li><a href="https://igoevent.com/crm/promoters">Promoters</a></li>-->
        </ul>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-lg-2 col-lg-offset-1"><p>Связаться с нами:</p></div>
            <div class="col-xs-12 col-lg-2"><p><a href="mailto:v@igoevent.com">v@igoevent.com</a></p></div>
            <div class="col-xs-12 col-lg-2"><p><a href='https://wa.me/79254244207?text=Здравствуйте. Пишу вам с igoevent.com'>what's app</a></p></div>
            <div class="col-xs-12 col-lg-2"><p><a href='https://vk.com/im?sel=-136527321'>vk.com/igoevent</a></p></div>
            <!--<div class="col-xs-12 col-lg-2"><p><a href='https://facebook.com/vitaliykhitrov'>facebook.com/vitaliykhitrov</a></p></div> -->
        </div>
    </div>

    <div class="bg_lgrey">
        <div class="container">
            <div class="col-xs-6 col-lg-2 m0"><p>&copy; iGoEvent <?= date('Y') ?></p></div>
            <div class="col-xs-6 col-lg-3 pull-right"><p>Разработка: <a href="http://vitalhit.com/">vitalhit</a></p>
            </div>
            <div class="col-xs-12 col-lg-1 tac">
                <!-- counter -->
                
            </div>
        </div>
    </div>
</footer>

<div class="popup pop_quest popup_d">
    <form id="quest_form" method="POST">
        <h3>Есть вопрос по этой странице? <?= Yii::$app->user->id ?></h3>
        <div class="col-xs-12">
            <input type="hidden" name="page" id="quest_page" value="">
            <input type="hidden" name="user_id" value="<?= Yii::$app->user->id ?>">
            <input type="hidden" name="task_id" id="quest_task" value="">
            <textarea class="pull-left form-control" name="text" id="quest_text"></textarea>
            <div class="clear mt20"></div>
            <br><select size="2" multiple name="for_user_id">
                <!--<option disabled>Выберите</option> -->
                <option selected value="5506">Аккаунт менеджеру</option>
                <option value="5">Тех. поддержке</option>
            </select>

            <div class="btn btn-success ma w200 mt20 submit">Отправить</div>
        </div>
    </form>
</div>
<div class="overlay"></div>

<?php $this->endBody() ?>
<script src="/js/main.js"></script>
<script src="/js/stacktable.js"></script>

</body>
</html>
<?php $this->endPage() ?>
