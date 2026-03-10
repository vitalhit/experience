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
<!-- layouts/admin.php -->
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
    <?php $menuItems = [
            ['label' => '<span class="glyphicon glyphicon-question-sign"></span>', 'url' => ['#'], 'options' => ['class' => 'quest'], 'encode'=>false],
            ['label' => '<span class="badge badge_feed">'.FeedWidget::count().'</span>',
                'encode'=>false,
                'url' => ['/admin/attantion/feedback?status=1'],
                'visible'=>FeedWidget::count(),
            ],
            ['label' => '<span class="glyphicon glyphicon-home"></span>', 'url' => ['/crm/company'], 'encode'=>false],
            ['label' => 'Внимание', 'url' => ['/admin/attantion/feedback']],
            ['label' => 'Page', 'url' => ['/admin/page/index'], 'options' => ['class' => 'new']],
            ['label' => 'Img', 'url' => ['/admin/img/index'], 'options' => ['class' => 'new']],
            ['label' => '<span class="glyphicon glyphicon-briefcase"></span>', 'url' => ['/admin/companies/index'],  'encode'=>false],
            ['label' => 'Разделы', 'url' => ['/admin/section/index']],
            ['label' => 'Билеты', 'url' => ['/admin/tickets/index']],
            ['label' => '<span class="glyphicon glyphicon-folder-open"></span>', 'url' => ['/admin/projects/index'],  'encode'=>false],
            ['label' => '<span class="badge badge_check">'.TaskforcheckWidget::count().'</span>',
                'encode'=>false,
                'url' => ['/admin/tasks/index?status=5&creator='.Yii::$app->user->id],
                'visible'=>TaskforcheckWidget::count(),
            ],
            ['label' => '<span class="badge badge_new">'.TaskformeWidget::count().'</span>',
                'encode'=>false,
                'url' => ['/admin/tasks/index?status=1&status2=2&owner='.Yii::$app->user->id],
                'visible'=>TaskformeWidget::count(),
            ],
            ['label' => '<span class="glyphicon glyphicon-home"></span>', 'url' => ['/admin/places/index'],  'encode'=>false],
            ['label' => 'Финансы', 'url' => ['/admin/finance/index'], 'options' => ['class' => 'new'], 'items' => [
                ['label' => 'Компании', 'url' => ['/admin/finance/company']],
                ['label' => 'Выплаты', 'url' => ['/admin/finance/index']],
                ['label' => 'Акты', 'url' => ['/admin/attantion/index']],
            ]],

            ['label' => 'Статистика', 'url' => ['/admin/statistics/index'], 'items' => [
                ['label' => 'Сводная', 'url' => ['/admin/statistics/index']],
                ['label' => 'Смены', 'url' => ['/admin/statistics/mystat']],
                ['label' => 'Напоминания', 'url' => ['/admin/statistics/messages']],
                ['label' => 'Отправка писем', 'url' => ['/admin/statistics/sendticket']],
                ['label' => 'Лог билетов', 'url' => ['/admin/statistics/logpay']],
                ['label' => 'Лог страниц', 'url' => ['/admin/statistics/logpage']],
            ]],

            ['label' => 'Права', 'url' => ['/rbac/user'], 'encode'=>false]
    ];


    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/login/']];
    } else {
        $menuItems[] = ['label' => 'Выход', 'url' => ['/site/logout/']];
    }

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

    // тут подродно о меню http://www.webapplex.ru/vidzhet-menu-v-yii-frejmvorke-2.x


    // NavBar::begin([
    //     'brandLabel' => 'АДМИНКА',
    //     'brandUrl' => Yii::$app->homeUrl.'admin',
    //     'options' => [
    //         'class' => 'navbar navbar-fixed-top navbar-inverse',
    //     ],
    // ]);
    // echo Nav::count([
    //     'options' => ['class' => 'navbar-nav navbar-right'],
    //     'items' => Helper::filter($menuItems),
    // ]);
    // NavBar::end();
    ?>

    <div class="container">
        <?= $content ?>
    </div>
</div>

    <footer class="footer">
        <div class="bg_grey">
            <ul class="fmenu">
                <li><a href="/crm/projects/my">Проекты</a></li>
                <li><a href="/crm/tasks/my">Задачи</a></li>
                <li><a href="/crm/tasks/my?status=1&status2=2&owner=<?= Yii::$app->user->id ?>">Мне</a></li>
                <li><a href="/crm/tasks/my?status=1&status2=2&creator=<?= Yii::$app->user->id ?>">Мои</a></li>
                <li><a href="/crm/events">Даты</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="row">               
                <div class="col-xs-12 col-lg-2 col-lg-offset-1"><p>Связаться с нами:</p></div>
                <div class="col-xs-12 col-lg-2"><p><a href="mailto:">v@igoevent.com</a></p></div>
                <div class="col-xs-12 col-lg-2"><p><a href='https://wa.me/79254244207?text=Здравствуйте. Пишу вам с igoevent.com'>what's app</a></p></div>
                <div class="col-xs-12 col-lg-2"><p><a href='https://vk.com/vitalhit'>vk.com/vitalhit</a></p></div>
                <!--<div class="col-xs-12 col-lg-2"><p><a href='https://facebook.com/vitaliykhitrov'>facebook.com/vitaliykhitrov</a></p></div>-->
            </div>
        </div>

        <div class="bg_lgrey">
            <div class="container">
                <div class="col-xs-6 col-lg-2 m0"><p>&copy; iGoEvent <?= date('Y') ?></p></div>
                <div class="col-xs-6 col-lg-3 pull-right"><p> Разработка: <a href="http://vitalhit.com/">vitalhit</a></p>
                </div>
                <div class="col-xs-12 col-lg-1 tac">
                    <noindex>
                        <script type="text/javascript">
                        document.write("<a rel='nofollow' href='//www.liveinternet.ru/click' "+
                        "target=_blank><img src='//counter.yadro.ru/hit?t26.1;r"+
                        escape(document.referrer)+((typeof(screen)=="undefined")?"":
                        ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                        screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                        ";h"+escape(document.title.substring(0,150))+";"+Math.random()+
                        "' alt='' title='LiveInternet: показано число посетителей за"+
                        " сегодня' "+
                        "border='0' width='88' height='15'><\/a>")
                        </script>
                    </noindex>
                </div>
            </div>
        </div>
    </footer>

    <div class="popup pop_quest popup_d">
        <form id="quest_form" method="POST">
            <h3>Есть вопрос по этой странице?</h3>
            <div class="col-xs-12">
                <input type="hidden" name="page" id="quest_page" value="">
                <input type="hidden" name="for_user_id" id="quest_for_user_id" value="5506">
                <textarea class="pull-left form-control" name="text" id="quest_text"></textarea>

                <br><select size="2" multiple name="for_user_id">

                    <option selected value="5506">Аккаунт менеджеру</option>
                    <option value="5">Тех. поддержке</option>
                </select>

                <div class="clear mt20"> </div>

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