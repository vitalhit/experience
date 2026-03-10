<?php

namespace app\controllers;

use Yii;
use app\models\Biblioevents;
use app\models\Bookingapi;
use app\models\BookList;
use app\models\Events;
use app\models\LogPay;
use app\models\Persons;
use app\models\Promocode;
use app\models\Rooms;
use app\models\Rents;
use app\models\Smena;
use app\models\Seats;
use app\models\Seatings;
use app\models\Tickets;
use app\models\Tour;
use app\models\Users;
use app\models\Vk;
use app\Services\Ticket\SecretcodeService;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Da\QrCode\QrCode;
use yii\web\Response;

class ApiController extends Controller
{

    public $enableCsrfValidation = false; // проверка формы на подленные данные

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
            ],
        ];
    }

    // API для ВИТРИНЫ
    // ------------------------------------------------------------------------------

    // Все события этой компании
    public function actionEventsByCompany($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $events = Biblioevents::find()->select(['biblioevents.id', 'biblioevents.name', 'biblioevents.url'])->where(['biblioevents.company_id' => $id])->joinWith(['activeevents'])->asArray()->all();
        return ['events' => $events];
    }


    // Все туры этой компании
    public function actionToursByCompany($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $events = Tour::find()->where(['tour.company_id' => $id, 'tour.status' => 1])->joinWith('events')->orderBy('tour.date_start')->asArray()->all();
        return ['events' => $events];
    }


    // Получаем билеты в дате
    public function actionTicketForm($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $event = Events::findOne($id);
        if (empty($event)) {
            return ['ticketForm' => null];
        }

        $seats = Seats::find()
        ->where(['event_id' => $event->id])
        ->andWhere(['or', ['promocode' => null], ['promocode' => '']])
        ->andWhere(['row' => null])
        ->all();

        // Добавим сбор к стоимости билета
        $newseats = [];
        foreach ($seats as $seat) {
            $seat->price = $seat->price + ($seat->price * $event->duty / 100);
            $tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('status > 0')->sum('count');
            $count = $seat->count - $tickets;
            if ($count > 0) {
                $newseats[] = $seat;
            }
        }

        return ['ticketForm' => $newseats];
    }


    // ПОКУПКА БИЛЕТА ПО АПИ
    // ------------------------------------------------------------------------------

    // Получаем даты в событии
    public function actionDates($id = false, $list_id = false)
    {
        $id = Yii::$app->request->get('id');

        $events = Events::find()
        ->joinWith('seats')
        ->where('events.event_id = :eid', [':eid' => $id])
        ->andwhere('status = 1')
        ->andwhere(['>', 'seats.count', '0'])
        ->andwhere('DATE(date) >= DATE(NOW())')
        ->orderBy('date')->all();

        $biblioevent = Biblioevents::find()->where(['id' => $id])->one();
        $alienevents = Null;

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->renderPartial('dates.twig', ['events' => $events, 'list_id' => $list_id, 'biblioevent' => $biblioevent,]);
    }


    // Получаем билеты в дате
    public function actionForm($id)
    {
        $event = Events::findOne($id);
        $biblioevent = Null;
        $newseats = Null;
        if ($event->date >= date("Y-m-d")) {
            $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();

            $seats = Seats::find()
            ->where(['event_id' => $event->id])
            ->andWhere(['or', ['promocode' => null], ['promocode' => '']])
            ->andWhere(['row' => null])
            ->all();

            foreach ($seats as $seat) {
                // Добавим сбор к стоимости билета
                $seat->price = $seat->price + ($seat->price * $event->duty / 100);
                $tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('status > 0')->sum('count');
                $count = $seat->count - $tickets;
                if ($count > 0) {
                    $newseats[] = $seat;
                }
            }

            $s_sort = Seats::SortSeats($event);
            $color = Seats::ColorSeats($event);

            $zal = null;
            if (!empty($event->zal)) {
                $zal = $event->zal;
            }

            // Есть ли билет с промокодом
            $promo = Seats::find()->where(['event_id' => $event->id])->andWhere(['not', ['promocode' => null]])->andWhere(['not', ['promocode' => '']])->all();
            if (empty($promo)) {
                $promo = null;
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $this->renderPartial('form.twig', [
                'seats' => $newseats,
                'biblioevent' => $biblioevent,
                'event' => $event,
                'promo' => $promo,
                's_sort' => $s_sort,
                'color' => $color,
                'zal' => $zal,
                'scheme' => $biblioevent->image
            ]);


        }
    }

    // Получаем билеты в дате
    public function actionFormf($id)
    {
        $event = Events::findOne($id);
        if ($event->date >= date("Y-m-d")) {
            $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();

            $seats = Seats::find()
            ->where(['event_id' => $event->id])
            ->andWhere(['or', ['promocode' => null], ['promocode' => '']])
            ->andWhere(['row' => null])
            ->all();

            foreach ($seats as $seat) {
                // Добавим сбор к стоимости билета
                $seat->price = $seat->price + ($seat->price * $event->duty / 100);
                $tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('status > 0')->sum('count');
                $count = $seat->count - $tickets;
                if ($count > 0) {
                    $newseats[] = $seat;
                }
            }


            $s_sort = Seats::SortSeats($event);
            $color = Seats::ColorSeats($event);

            $zal = null;
            if (!empty($event->zal)) {
                $zal = $event->zal;
            }

            // Есть ли билет с промокодом
            $promo = Seats::find()->where(['event_id' => $event->id])->andWhere(['not', ['promocode' => null]])->andWhere(['not', ['promocode' => '']])->all();
            if (empty($promo)) {
                $promo = null;
            }

            //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $this->renderPartial('formf.twig', [
                'seats' => $newseats,
                'biblioevent' => $biblioevent,
                'event' => $event,
                'promo' => $promo,
                's_sort' => $s_sort,
                'color' => $color,
                'zal' => $zal,
                'scheme' => $biblioevent->image
            ]);


        }
    }


    // Форма с выбранным местом
    public function actionSeat($id, $seatid)
    {
        $event = Events::findOne($id);
        if ($event->date >= date("Y-m-d")) {
            $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
            $seat = Seats::find()->where(['event_id' => $event->id, 'id' => $seatid])->one();
            // Добавим сбор к стоимости билета
            $seat->price = $seat->price + ($seat->price * $event->duty / 100);

            $tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('status > 0')->sum('count');
            $count = $seat->count - $tickets;

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('seat.twig', [
                'seat' => $seat,
                'count' => $count,
                'biblioevent' => $biblioevent,
                'event' => $event
            ]);
        }
    }


    // Промо билеты
    public function actionPromocode($promocode, $event_id)
    {
        $event = Events::findOne($event_id);
        if ($event->date >= date("Y-m-d H:i:s")) {
            $seats = Seats::find()->where(['promocode' => $promocode, 'event_id' => $event->id])->all();
            foreach ($seats as $seat) {
                // Добавим сбор к стоимости билета
                $seat->price = $seat->price + ($seat->price * $event->duty / 100);
                $tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('status > 0')->andWhere('status > 0')->sum('count');
                $count = $seat->count - $tickets;
                if ($count > 0) {
                    $promoseats[] = $seat;
                }
            }
            // $time = sprintf('%0.2f',Yii::getLogger()->getElapsedTime());
            // file_put_contents('atime.txt', PHP_EOL.Date('d.m.Y H:i:s - промокод: ').$time.' сек', FILE_APPEND);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('promotickets.twig', [
                'promo' => $promoseats,
            ]);
        }
    }


    // Виджет2 - с селектом даты
    public function actionDateselect($id = false, $list_id = false)
    {
        $events = Events::find()
        ->joinWith('seats')
        ->where('events.event_id = :eid', [':eid' => $id])
        ->andwhere('DATE(date) >= DATE(NOW())')
        ->andwhere('status = 1')
        ->andwhere(['>', 'seats.count', '0'])
        ->orderBy('date')->all();
        $event = Events::find()->where(['events.id' => $events[0]->id])->joinWith('biblioevents.places')->one();

        if ($events[0]->date >= date("Y-m-d")) {
            $biblioevent = Biblioevents::find()->where(['id' => $events[0]->event_id])->one();
            $seats = Seats::find()->where(['event_id' => $events[0]->id])->andWhere(['or', ['promocode' => null], ['promocode' => ''],])->all();
            foreach ($seats as $seat) {
                // Добавим сбор к стоимости билета
                $seat->price = $seat->price + ($seat->price * $event->duty / 100);
                $tickets = Tickets::find()->where(['seat_id' => $seat->id])->sum('count');
                $count = $seat->count - $tickets;
                if ($count > 0) {
                    $newseats[] = $seat;
                }
            }

            // Есть ли билет с промокодом
            $promo = Seats::find()->where(['event_id' => $events[0]->id])->andWhere(['not', ['promocode' => null]])->andWhere(['not', ['promocode' => '']])->all();
            if (!empty($promo)) {
                foreach ($promo as $seat) {
                    // Добавим сбор к стоимости билета
                    $seat->price = $seat->price + ($seat->price * $event->duty / 100);
                    $tickets = Tickets::find()->where(['seat_id' => $seat->id])->sum('count');
                    $count = $seat->count - $tickets;
                    if ($count > 0) {
                        $newpromo[] = $seat;
                    }
                }
            } else {
                $newpromo = null;
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('dateselect.twig', [
                'seats' => $newseats,
                'biblioevent' => $biblioevent,
                'events' => $events,
                'event' => $event,
                'promo' => $newpromo,
                'scheme' => $biblioevent->image
            ]);
        }
    }


    // Виджет2 - выбор даты
    public function actionFormselect($id)
    {
        $event = Events::findOne($id);
        if ($event->date >= date("Y-m-d")) {
            $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
            $seats = Seats::find()->where(['event_id' => $event->id])->andWhere(['or', ['promocode' => null], ['promocode' => ''],])->all();
            foreach ($seats as $seat) {
                // Добавим сбор к стоимости билета
                $seat->price = $seat->price + ($seat->price * $event->duty / 100);
                $tickets = Tickets::find()->where(['seat_id' => $seat->id])->sum('count');
                $count = $seat->count - $tickets;
                if ($count > 0) {
                    $newseats[] = $seat;
                }
            }

            // Есть ли билет с промокодом
            $promo = Seats::find()->where(['event_id' => $event->id])->andWhere(['not', ['promocode' => null]])->andWhere(['not', ['promocode' => '']])->all();
            if (empty($promo)) {
                $promo = null;
            }


            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('formselect.twig', [
                'seats' => $newseats,
                'biblioevent' => $biblioevent,
                'event' => $event,
                'promo' => $promo,
                'scheme' => $biblioevent->image
            ]);
        }
    }


    // При покупке билетов получаем информацию о доступных местах - непроданных билетах
    public function actionCountseat($userticket, $seat)
    {
        $oneseat = Seats::find()->where(['id' => $seat])->one();
        $event = Events::find()->where(['id' => $oneseat->event_id])->one();
        $bib = Biblioevents::find()->where(['id' => $event->event_id])->one();
        // file_put_contents('test.txt', PHP_EOL.Date('d.m.Y H:i:s - место: ').$bib->name, FILE_APPEND);

        if (!empty($oneseat)) {
            $tickets = Tickets::find()->where(['seat_id' => $oneseat->id])->andWhere('status > 0')->sum('count');
        } else {
            $tickets = 0;
        }

        $count = $oneseat->count - $tickets; // доступно билетов
        if ($count >= $oneseat->promolimit and $oneseat->promolimit > 0) { // если билетов больше чем лимит - то берем за основу лимит
            $count = $oneseat->promolimit;
        }

        if ($count > 0 and $userticket > 0) {
            if ($count >= $userticket) {
                $count = $userticket;
            } 
        } else {
            $count = 0;
        }

        return $count;
    }


    // Есть ли у нас такой гость по mail
    public function actionUseresset()
    {
        $mail = Yii::$app->request->get('mail');
        $company_id = Yii::$app->request->get('company_id');
        $person = Persons::find()->where(['mail' => $mail])->one();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($person)) {
            return $person;
        }
        return;
    }


    // Покупка билетов с местам
    public function actionTicketseat()
    {
     
        // Защита от DDoS

     $ip = null;
     if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = json_encode($_SERVER['REMOTE_ADDR']);
            /* Vitalii отключил эту функцию
            $ban = LogPay::checkIp($ip);
            if (!empty($ban)) { return;}
            */
        }

        // Проверка есть ли событие
        $event_id = null;
        if (!empty($_POST) && !empty($_POST['data'])) {
            $event_id = $_POST['data']['event_id'];
            $event = Events::find()->filterWhere(['events.id' => $event_id])
            ->joinWith(['biblioevents', 'biblioevents.places', 'biblioevents.letterBuy'])
            ->one();
        }

        if (empty($event)) {
            return null;
        }

        if (empty($_POST) or empty($_POST['data'])) {
            return null;
        }

        if (!empty($_POST['data']['id'])) {
            $person = Persons::find()
            ->filterWhere(['id' => $_POST['data']['id'], 'mail' => $_POST['data']['mail']])
//                ->andWhere(['>', 'status', 0]) // TODO Виталь проверь статус
            ->one();
        } else {
            // Создаем нового гостя
            $_POST['company_id'] = $_POST['data']['company_id'];
            $_POST['name'] = trim($_POST['data']['name']);
            $_POST['second_name'] = trim($_POST['data']['second_name']);
            $_POST['mail'] = trim($_POST['data']['mail']);
            //$_POST['phone'] = trim($_POST['data']['phone']);

            $_POST['phone'] = trim($_POST['data']['phone']);
            $phone_temp = preg_replace('/\D*/', '', $_POST['phone']);
            if (substr($phone_temp, 0, 1) == 8) $phone_temp[0] = 7;
            $_POST['phone'] = $phone_temp;

            $person = Persons::createPerson($_POST);
        }

        if (empty($person)) {
            $message = 'Юзер не создан';
            return null;
        }

        $pid = $person->id;

        $report_about_person = $person->name . " " . $person->second_name . " +" . $person->phone . " " . $person->mail . ": ";


        // Перебираем места, которые выбрал гость
        $sum = 0;
        $order_id = 't' . uniqid();

        if (!empty($_POST['seats']) && is_array($_POST['seats'])) {
            foreach ($_POST['seats'] as $k => $v) {
                // Место
                $s = Seats::find()->where(['event_id' => $event->id, 'id' => $v['id']])->one();
                $base_price = $s->price + ($s->price * $event->duty / 100);
                if ($base_price != $v['sum']) {
                    file_put_contents('test.txt', PHP_EOL . 'Ошибка в стоимости места: ' . $v['id'], FILE_APPEND);
                }

                $ticket = new Tickets();
                $ticket->user_id = $pid;
                $ticket->user = $person->user_id;
                $ticket->person_id = $person->id;
                $ticket->order_id = $order_id;
                $ticket->event_id = $_POST['data']['event_id'];
                $ticket->seat_id = $v['id'];
                $ticket->template_id = $s->template_id;
                $ticket->seat = $v['seat'];

                $ticket->money = $s->price;
                $ticket->count = 1;
                $ticket->summa = $s->price;
                $ticket->duty = $s->price * $event->duty / 100;
                $discount ='';
                    
                if (isset($_POST['data']['secretcode'])) {
                    $promo_service = new SecretcodeService();
                    $ticket =  $promo_service->discount($ticket, $_POST['data']['secretcode']);
                    $discount = $ticket->admin; 
                }

                $ticket->date = date("Y-m-d H:i:s");

                if (isset($_POST['data']['info']) && !empty($_POST['data']['info']))  {
                    $ticket->info = $_POST['data']['info'];
                }
                if (isset($_POST['data']['type']) && !empty($_POST['data']['type']))  {
                    $ticket->type = $_POST['data']['type'];
                }
                if (isset($_POST['data']['from_url']) && !empty($_POST['data']['from_url']))  {
                    $ticket->from_url = $_POST['data']['from_url'];
                }
                if ($_POST['data']['subscribe'] == 'on') {
                    $ticket->subscribe = 1;
                }
                if (isset($_POST['data']['utm_source']) && !empty($_POST['data']['utm_source']))  {
                    $ticket->utm_source = $_POST['data']['utm_source'];
                }
                if (isset($_POST['data']['utm_medium']) && !empty($_POST['data']['utm_medium']))  {
                    $ticket->utm_medium = $_POST['data']['utm_medium'];
                }
                if (isset($_POST['data']['utm_campaign']) && !empty($_POST['data']['utm_campaign']))  {
                    $ticket->utm_campaign = $_POST['data']['utm_campaign'];
                }
                if (isset($_POST['data']['utm_content']) && !empty($_POST['data']['utm_content']))  {
                    $ticket->utm_content = $_POST['data']['utm_content'];
                }
                if (isset($_POST['data']['utm_term']) && !empty($_POST['data']['utm_term']))  {
                    $ticket->utm_term = $_POST['data']['utm_term'];
                }
                $ticket->company_id = $_POST['data']['company_id'];
                $ticket->name = $_POST['data']['name'];
                $ticket->secondname = $_POST['data']['second_name'];
                $ticket->phone = $_POST['data']['phone'];
                $ticket->email = $_POST['data']['mail'];
                $ticket->status_come = 0;
                $ticket->status = 1;
                $ticket->type = 1;

                if ($ticket->save()) {
                    $ticket->barcode = str_pad(substr($ticket->event_id, 0, 6), 5, '0', STR_PAD_RIGHT) . substr($ticket->date, 17, 2) . str_pad(substr($ticket->id, 0, 6), 6, '0', STR_PAD_LEFT);
                    $ticket->save();

                    $buying_event = $event->biblioevents->name;

                    // Передаем в письмо и в спасибо!
                    if (!empty($event->biblioevents->letterBuy->theme)) {
                        $theme = $event->biblioevents->letterBuy->theme;
                    } else {
                        $theme = 'Спасибо за покупку! ' . $buying_event;
                    }
                    $date = date("d.m.Y H:i", strtotime($event->date));

                    $seats = array($ticket->id, $ticket->money, $ticket->count, $order_id);
                    $buying_seats[] = $seats;

                    if ($event->biblioevents->info_reg_after) {
                        $return = $event->biblioevents->info_reg_after . '<p>Ваши билеты вы можете найти на странице igoevent.com в разделе «<a href="https://igoevent.com/profile">Билеты</a>», так же на вашу почту будет отправлено письмо ссылкой на билеты в течение 20 минут.</p>';
                    } else {
                        $return = '<h2>Спасибо за покупку на ' . $buying_event . '<br>' . $date . '.<br>Ваши билеты вы можете найти на странице igoevent.com в разделе «<a href="https://igoevent.com/profile">Билеты</a>», так же на вашу почту будет отправлено письмо ссылкой на билеты в течение 20 минут.</h2>';
                    }

                    if ($event->biblioevents->link_bot) {
                        $return = $return . '<script>setTimeout( \'location="' . $event->biblioevents->link_bot . '";\', 7000 );</script>';
                    }

                    $sum = $sum + $ticket->summa;
                    
                } else {
                    
                    $return = '<h2>Покупка не удалась, попробуйте еще раз!</h2>';
                }
            };
        }

        if (!empty($_POST['tickets']) && is_array($_POST['tickets'])) {
            foreach ($_POST['tickets'] as $k => $v) {
                $co = $v['count'];
                if ($co < 11) {
                    $co = $co;
                } else {
                    $co = 10;
                }
                if ($co > 0) {
                    $count_error = 0;
                    while ($co > 0) {
                        $count_error = $count_error + 1;

                        // Место
                        $s = Seats::find()->where(['event_id' => $event->id, 'id' => $v['id']])->one();
                        $base_price = $s->price + ($s->price * $event->duty / 100);
                        if ($base_price != $v['sum']) { //file_put_contents('test.txt', PHP_EOL . 'Ошибка в стоимости места: ' . $v['id'], FILE_APPEND);
                    }

                    $ticket = new Tickets();
                    $ticket->user_id = $pid;
                    $ticket->user = $person->user_id;
                    $ticket->person_id = $person->id;
                    $ticket->order_id = $order_id;
                    $ticket->event_id = $_POST['data']['event_id'];
                    $ticket->seat_id = $v['id'];
                    $ticket->template_id = $s->template_id;

                    $ticket->seat = null;
                    $ticket->promocode = '';
                    $ticket->count = 1;
                    $ticket->money = $s->price;
                    $ticket->summa = $s->price;
                    $ticket->duty = $s->price * $event->duty / 100;

                    $discount ='';

                    if (isset($_POST['data']['secretcode'])) {
                        $promo_service = new SecretcodeService();
                        $ticket =  $promo_service->discount($ticket, $_POST['data']['secretcode']);
                        $discount = $ticket->admin; 
                    }

                    $ticket->date = date("Y-m-d H:i:s");

                    if (isset($_POST['data']['info']) && !empty($_POST['data']['info']))  {
                        $ticket->info = $_POST['data']['info'];
                    }
                    if (isset($_POST['data']['type']) && !empty($_POST['data']['type']))  {
                        $ticket->type = $_POST['data']['type'];
                    }
                    if (isset($_POST['data']['from_url']) && !empty($_POST['data']['from_url']))  {
                        $ticket->from_url = $_POST['data']['from_url'];
                    }
                    if ($_POST['data']['subscribe'] == 'on') {
                        $ticket->subscribe = 1;
                    }
                    
                    $ticket->utm_source = $_POST['data']['utm_source'];
                    $ticket->utm_medium = $_POST['data']['utm_medium'];
                    $ticket->utm_campaign = $_POST['data']['utm_campaign'];
                    $ticket->utm_content = $_POST['data']['utm_content'];
                    $ticket->utm_term = $_POST['data']['utm_term'];
                    $ticket->company_id = $_POST['data']['company_id'];
                    $ticket->name = $_POST['data']['name'];
                    $ticket->secondname = $_POST['data']['second_name'];
                    $ticket->phone = $_POST['data']['phone'];
                    $ticket->email = $_POST['data']['mail'];
                    $ticket->status = 1;

                    if ($ticket->save()) {
                        $ticket->barcode = str_pad(substr($ticket->event_id, 0, 6), 5, '0', STR_PAD_RIGHT) . substr($ticket->date, 17, 2) . str_pad(substr($ticket->id, 0, 6), 6, '0', STR_PAD_LEFT);
                        $ticket->save();

                        // Получаем название события, чтобы передать в письмо и в спасибо!
                        $buying_event = $event->biblioevents->name;

                        if (!empty($event->biblioevents->letterBuy->theme)) {
                            $theme = $event->biblioevents->letterBuy->theme;
                        } else {
                            $theme = 'Спасибо за покупку!! ' . $buying_event;
                        }
                        $date = date("d.m.Y H:i", strtotime($event->date));

                        $buying_event = $event->biblioevents->name;
                        $seats = array($ticket->id, $ticket->money, $ticket->count, $order_id);
                        $buying_seats[] = $seats;

                        if ($event->biblioevents->info_reg_after) {
                            $return = $event->biblioevents->info_reg_after . '<p>Ваши билеты вы можете найти на странице igoevent.com в разделе «<a href="https://igoevent.com/profile">Билеты</a>», так же на вашу почту будет отправлено письмо ссылкой на билеты в течение 20 минут..</p>';
                        } else {
                            $return = '<h2>Спасибо за покупку на ' . $buying_event . '<br>' . $date . '.<br>Ваши билеты вы можете найти на странице igoevent.com в разделе «<a href="https://igoevent.com/profile">Билеты</a>», так же на вашу почту будет отправлено письмо ссылкой на билеты в течение 20 минут..</h2>';
                        }

                        if ($event->biblioevents->link_bot) {
                            $return = $return . '<script>setTimeout( \'location="' . $event->biblioevents->link_bot . '";\', 7000 );</script>';
                        }

                        $sum = $sum + $ticket->summa;

                        $co = $co - 1;


                    } else {
                        
                        $return = '<h2>Покупка не удалась, попробуйте еще раз!</h2>';
                    }
                }
            }
        };
    }

    $tickets = Tickets::find()->where(['order_id' => $order_id])->all();

    // begin 12 сентября 2024 в поезде: готовлю выгрузку для отправки уведомлений в вк
    // $message_vk = '';

    // foreach ($tickets as $ticket) {
    //     $message_vk .=  $ticket->name . ' ' .  $ticket->price . '<br>';

    // }
    // end 

    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Если сумма > 0 - то оплата
    if ($sum > 0) {
        if ($event->biblioevents->vksend) {
            Vk::Send($report_about_person . 'Начата покупка на «' . $event->biblioevents->name . '»
            ' . '(' . $event->date . ')' 
            . ' https://igoevent.com/crm/biblioevents/view?id=' .$event->biblioevents->id, [$event->biblioevents->vksend]);
        }
        return $this->renderPartial('apipay.twig', [
            'user' => $person,
            'order_id' => $order_id,
            'event' => $event,
            'info' => $ticket->info,
            'tickets' => $tickets,
            'sum' => $sum,
            'discount' => $discount??Null
        ]);
    } else {
        if ($event->biblioevents->vksend) {
            Vk::Send($report_about_person . 'Регистрация гостя на событие "' . $event->biblioevents->name . ' https://igoevent.com/crm/biblioevents/view?id=' . $event->biblioevents->id . '"', [$event->biblioevents->vksend]);
        }
        return $return;
    }
}


public function actionTicketbuy()
{
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = json_encode($_SERVER['REMOTE_ADDR']);
    } else {
        $ip = null;
    }

        // Проверка есть ли id гостя
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $person = Persons::findOne($_POST['id']);
        if (empty($person)) {
            $person = Persons::createPerson($_POST);
        }
    } else {
        $person = Persons::createPerson($_POST);
    }
    $pid = $person->id;

        // Перебираем места, которые выбрал гость
    if (is_array($_POST['seat'])) {

        $sum = 0;
        $order_id = 't' . uniqid();

        foreach ($_POST['seat'] as $k => $v) {
            if ($v > 0) {
                while ($v > 0) {
                    $ticket = new Tickets();
                    $ticket->user = $person->user_id;
                    $ticket->person_id = $person->id; 
                    $ticket->user_id = $pid;
                    $ticket->order_id = $order_id;
                    $ticket->event_id = $_POST['event_id'];
                    $ticket->seat_id = $_POST['seat_id'][$k];
                    $ticket->money = $_POST['money'][$k];
                    $ticket->count = 1;
                    $ticket->summa = $ticket->money;
                    $ticket->date = date("Y-m-d H:i:s");
                    $ticket->info = $_POST['info'];
                    $ticket->type = $_POST['type']; // Оплата через яндекс кассу без вариантов
                    $ticket->from_url = $_POST['from_url'];
                    if ($_POST['subscribe'] == 'on') {
                        $ticket->subscribe = 1;
                    }
                    $ticket->utm_source = $_POST['utm_source'];
                    $ticket->utm_medium = $_POST['utm_medium'];
                    $ticket->utm_campaign = $_POST['utm_campaign'];
                    $ticket->utm_content = $_POST['utm_content'];
                    $ticket->utm_term = $_POST['utm_term'];
                    $ticket->company_id = $_POST['company_id'];
                    $ticket->name = $_POST['name'];
                    $ticket->secondname = $_POST['second_name'];
                    $ticket->phone = $_POST['phone'];
                    $ticket->email = $_POST['mail'];
                    
                    $ticket->status = 1;

                    if ($ticket->save()) {
                        $ticket->barcode = str_pad(substr($ticket->event_id, 0, 6), 5, '0', STR_PAD_RIGHT) . substr($ticket->date, 17, 2) . str_pad(substr($ticket->id, 0, 6), 6, '0', STR_PAD_LEFT);
                        $ticket->save();
                        // Получаем название события, чтобы передать в письмо и в спасибо!
                        $event = Events::find()->joinWith('biblioevents')->joinWith('biblioevents.places')->joinWith('biblioevents.letterBuy')
                        ->where(['events.id' => $ticket->event_id])->one();

                        $buying_event = $event->biblioevents->name;

                        if (!empty($event->biblioevents->letterBuy->theme)) {
                            $theme = $event->biblioevents->letterBuy->theme;
                        } else {
                            $theme = 'Спасибо за покупку!!! ' . $buying_event;
                        }
                        $date = date("d.m.Y H:i", strtotime($event->date));

                        $buying_event = $event->biblioevents->name;
                        $seats = array($ticket->id, $ticket->money, $ticket->count, $order_id);
                        $buying_seats[] = $seats;

                        $return = '<h2>Спасибо за покупку на ' . $buying_event . '<br>' . $date . '.<br>На вашу почту будет отправлен электронный билет!</h2>';

                        $sum = $sum + $ticket->summa; // Суммируем стоимость билетов для оплаты

                        $v = $v - 1;

                        } else {
                            $return = '<h2>Покупка не удалась, попробуйте еще раз!</h2>';
                        }
                    }
                }
            };
        }

        $tickets = Tickets::find()->where(['order_id' => $order_id])->all();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Если сумма > 0 - то оплата
        if ($sum > 0) {
            return $this->renderPartial('apipay.twig', [
                'user' => $person,
                'order_id' => $order_id,
                'event' => $event,
                'info' => $ticket->info,
                'tickets' => $tickets,
                'sum' => $sum,
                'discount' => $discount??Null
            ]);
        } else {
            return $return;
        }
    }

    // Создадим ЛК по крону!
    public function actionCronlk()
    {
        //file_put_contents('test.txt', PHP_EOL . PHP_EOL . 'Крон', FILE_APPEND);

        $persons = Persons::find()->where('user_id is null')->andWhere('status is Null')->andWhere(['like', 'mail', '@'])->limit(1)->all();
        $i = 0;
        foreach ($persons as $one) {
            $person = Persons::findOne($one->id);
            $user = Users::createUser($person);
            if (!empty($user->id)) {
                $person->user_id = $user->id;
                $person->serv_info = $person->serv_info.'крон создал user_id '.$person->user_id;
                $person->status = '1';
                if (!$person->save()) {
                    // file_put_contents('test.txt', PHP_EOL.json_encode($person->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
                }
            } else {
                // file_put_contents('test.txt', PHP_EOL . 'НЕТ ЮЗЕРА', FILE_APPEND);
            }
            // sleep(1);
            $i++;
            echo $person->mail . ' - ' . $person->id . '<br>';
            $ids[] = $person->id;
        }
        if (!empty($ids)) {
            $logpay = LogPay::setLog("$i", 'ЛК созданы, id ' . json_encode($ids), null, 3);
        }
        echo $i;
    }


    // Зафиксируем переход на яндекс для оплаты
    public function actionYandex($order_id, $user_id)
    {
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = json_encode($_SERVER['REMOTE_ADDR']);
        } else {
            $ip = null;
        }
        $logpay = LogPay::setLog($order_id, 'Перешел на яндекс', $ip, 1);
    }


    // --------------------------------
    // BOOK FORM - оставить заявку


    // Получаем форму для заявки
    public function actionBookform($id = false, $list_id = false, $template = false)
    {
        // file_put_contents('TEST.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ').json_encode($_GET,JSON_UNESCAPED_UNICODE), FILE_APPEND);
        if (isset($id)) {
            $biblioevent = Biblioevents::findOne($id);
            $company_id = $biblioevent->company_id;
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $this->renderPartial('bookformtwo.twig', ['biblioevent' => $biblioevent, 'template' => $template, 'company_id' => $company_id]);
    }

    // Получаем форму для заявки - вторая версия
    public function actionBookformtwo($id = false, $list_id = false)
    {
        // file_put_contents('TEST.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ').json_encode($_GET,JSON_UNESCAPED_UNICODE), FILE_APPEND);
        if (isset($id)) {
            $biblioevent = Biblioevents::findOne($id);
            $company_id = $biblioevent->company_id;
        }
        if (isset($list_id)) {
            $list = BookList::findOne($list_id);
            if (empty($company_id)) {
                $company_id = $list->company_id;
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $this->renderPartial('bookform2.twig', ['biblioevent' => $biblioevent, 'list' => $list, 'company_id' => $company_id]);
    }


    public function actionBooking()
    {

        $booking = new Bookingapi();

        // Проверка есть ли id гостя
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $person = Persons::findOne($_POST['id']);
        } else {
            // Создаем нового гостя
            $person = Persons::createPerson($_POST);
        }
        
        if (isset($_POST['company_id'])) {  
            $pid = $person->id;
            $booking->person_id = $pid;
        }
        
        
        
        if (isset($_POST['company_id'])) { $booking->company_id = $_POST['company_id']; }

        if (isset($_POST['biblioevent_id'])) { $booking->biblioevent_id = $_POST['biblioevent_id']; }
        if (isset($_POST['list_id'])) { $booking->list_id = $_POST['list_id'];}

        if (isset($_POST['name'])) { $booking->name = trim($_POST['name']);}
        if (isset($_POST['second_name'])) { $booking->second_name = trim($_POST['second_name']);}
        if (isset($_POST['mail'])) { $booking->mail = trim($_POST['mail']);}
        if (isset($_POST['phone'])) { $booking->phone = trim($_POST['phone']); }
        
        if (isset($_POST['message2']) and isset($_POST['wantdate'])) {
            $booking->message = $_POST['wantdate'] . ' \n\r' . $_POST['message'] . ' \n\r' . $_POST['message2'];

        } elseif (isset($_POST['wantdate'])){
            $booking->message = $_POST['wantdate'] . '' . $_POST['message'];
        } else {
            $booking->message = $_POST['message'];
        }
        

        if (isset($_POST['brand'])) {$booking->brand = $_POST['brand'];}
        if (isset($_POST['link_site'])) {$booking->link_site = $_POST['link_site'];}
        if (isset($_POST['from_url'])) {$booking->from_url = $_POST['from_url'];}
        if (isset($_POST['subscribe'])) { 
            if ($_POST['subscribe'] == 'on') {
                $booking->subscribe = 1;
            }
        }
        
        if (isset($_POST['utm_source'])) {$booking->utm_source = $_POST['utm_source']; }
        if (isset($_POST['utm_medium'])) {$booking->utm_medium = $_POST['utm_medium'];}
        if (isset($_POST['utm_campaign'])) {$booking->utm_campaign = $_POST['utm_campaign'];}
        if (isset($_POST['utm_content'])) {$booking->utm_content = $_POST['utm_content'];}
        if (isset($_POST['utm_term'])) {$booking->utm_term = $_POST['utm_term'];}
        
        $booking->domain = "igoevent.com";

        $booking->time = date('Y-m-d H:i:s');
        $booking->status_id = 1;
        if (isset($_POST['link_insta'])) {$booking->link_insta = $_POST['link_insta'];}
        if (isset($_POST['link_vk'])) {$booking->link_vk = $_POST['link_vk'];}
        if (isset($_POST['link_fb'])) {$booking->link_fb = $_POST['link_fb'];}
        if (isset($_POST['info_wish'])) {$booking->info_wish = $_POST['info_wish'];}
        if (isset($_POST['info_goal'])) {$booking->info_goal = $_POST['info_goal'];}
        if (isset($_POST['info_job'])) {$booking->info_job = $_POST['info_job'];}


        if ($booking->save()) {

            $biblioevent = Biblioevents::find()->where(['id' => $_POST['biblioevent_id']])->one();

            if ($biblioevent->id == 158) {
                $return = '<h2>Спасибо за вашу заявку.</h2>';
            } // костыль ;-)

            $return = '<h2>Спасибо за вашу заявку. Мы отправим вам письмо, когда билеты появятся в продаже.</h2>';


            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            

            if ($biblioevent->vksend != '') {
                Vk::Send('Заявка от '.$booking->second_name.' '.$booking->name. ' ' . $booking->mail . ' ' . $booking->phone .': https://igoevent.com/crm/biblioevents/view?id=' . $_POST['biblioevent_id'] . '<br>' . $_POST['from_url'], [trim($biblioevent->vksend)]);
            }

            return $return;
        }
    }



    // --------------------------------
    // Забронировать ЗАЛ


    public function actionBron($id)
    {
        $room = Rooms::find()->where(['rooms.id' => $id])->joinWith('rents')->one();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->renderPartial('bron.twig', ['room' => $room]);
    }


    // Есть ли у нас такой гость по mail
    public function actionRoomdate($date, $room_id)
    {
        $room = Rooms::find()->where(['id' => $room_id])->one();
        $rents = Rents::find()->where(['room_id' => $room_id])->andWhere(['date' => $date])->all();

        $start = strtotime($room->time_start) - strtotime("00:00:00");
        $end = strtotime($room->time_end) - strtotime("00:00:00");
        $step = strtotime($room->time_step) - strtotime("00:00:00");

        //кол-во колонок для отображения расписания
        if ($step == 1800) {
            $col = 6;
        } elseif ($step == 900) {
            $col = 3;
        } elseif ($step == 3600) {
            $col = 12;
        }

        while ($start < $end) {
            $slot_end = $start + $step;
            $slots[] = array('start' => gmdate("H:i:s", $start), 'end' => gmdate("H:i:s", $slot_end));
            $start = $slot_end; // изменим старт для следующего слота
        }

        foreach ($slots as $slot) {
            $status = 'free';
            foreach ($rents as $rent) {
                if ($slot['start'] == $rent->start) {
                    $status = 'busy';
                }
            }
            $fin_slots[] = array('start' => $slot['start'], 'end' => $slot['end'], 'status' => $status);
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->renderPartial('rentslots.twig', ['rents' => $rents, 'room' => $room, 'fin_slots' => $fin_slots, 'col' => $col]);
    }


    // Бронируем
    public function actionRoomrent()
    {

        // Проверка есть ли id гостя
        if (isset($_POST['data']['id']) && !empty($_POST['data']['id'])) {
            $person = Persons::findOne($_POST['data']['id']);
        } else {
            // Создаем нового гостя
            $_POST['company_id'] = $_POST['data']['company_id'];
            $_POST['name'] = trim($_POST['data']['name']);
            $_POST['second_name'] = trim($_POST['data']['second_name']);
            $_POST['mail'] = trim($_POST['data']['mail']);
            $_POST['phone'] = trim($_POST['data']['phone']);
            $person = Persons::createPerson($_POST);
        }
        $pid = $person->id;

        $room = Rooms::find()->where(['id' => $_POST['data']['room_id']])->one();
        // Перебираем слоты, которые выбрал гость
        if (is_array($_POST['rent'])) {
            $sum = 0;
            $order_id = 'r' . uniqid();
            foreach ($_POST['rent'] as $key => $value) {
                if (($key + 1) % 2 != 0) {
                    $start = $value;
                } elseif (($key + 1) % 2 == 0) {
                    $end = $value;

                    $rent = new Rents();
                    $rent->order_id = $order_id;
                    $rent->room_id = $_POST['data']['room_id'];
                    $rent->person_id = $pid;
                    $rent->date = $_POST['data']['datep'];
                    $rent->start = $start;
                    $rent->end = $end;
                    $rent->summa = $room->money;
                    $rent->type = 9;
                    $rent->info = $_POST['data']['info'];
                    $rent->create_at = date("Y-m-d H:i:s");
                    $rent->from_url = $_POST['data']['from_url'];
                    $rent->utm_source = $_POST['data']['utm_source'];
                    $rent->utm_medium = $_POST['data']['utm_medium'];
                    $rent->utm_campaign = $_POST['data']['utm_campaign'];
                    $rent->utm_content = $_POST['data']['utm_content'];
                    $rent->utm_term = $_POST['data']['utm_term'];
                    $rent->status = 1;
                    $sum = $sum + $room->money;
                }
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->renderPartial('apirentpay.twig', [
                'user' => $person,
                'order_id' => $order_id,
                'info' => $rent->info,
                'room' => $room,
                'sum' => $sum
            ]);
        }
    }


    // БЕСПЛАТНАЯ регистрация через форму на стороннем сайте
    public function actionBookingapi()
    {

        $smena = Smena::find()->where(['type' => 1])->andWhere('end is Null')->one();

        if ($_POST) {
            $book = new Bookingapi();

            $book->from_url = $_POST['from_url'];
            $book->name = trim($_POST['name']);
            $book->mail = trim($_POST['mail']);
            $book->phone = trim($_POST['phone']);
            $book->message = $_POST['message'];
            $book->utm_source = $_POST['utm_source'];
            $book->utm_medium = $_POST['utm_medium'];
            $book->utm_campaign = $_POST['utm_campaign'];
            $book->utm_content = $_POST['utm_content'];
            $book->utm_term = $_POST['utm_term'];
            $book->smena_id = $smena->id;
            $book->status_id = 1;

            $book->save();

            $return = 'Спасибо за регистрацию!';
        } else {
            $return = 'Неудача!';
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $return;

    }


    // Frames
    public function actionDateframe($id)
    {
        $id = Yii::$app->request->get('id');
        $events = Events::find()->joinWith('seatings')->where(['event_id' => $id])->andwhere('DATE(date) >= DATE(NOW())')->orderBy(['date' => SORT_ASC])->all();
        return $this->renderPartial('dateframe.twig', ['events' => $events]);
    }


    public function actionFormframe($id)
    {
        $event = Events::findOne($id);
        if ($event->date >= date("Y-m-d H:i:s")) {

            if ($event->seating_id) {
                $seatings = Seatings::find()->where(['id' => $event->seating_id])->one();
            } else {
                $biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
                $seatings = Seatings::find()->where(['id' => $biblioevent->seating_id])->one();
            }

            $seats = Seats::find()->where(['seating_id' => $seatings->id])->all();

            return $this->renderPartial('formframe.twig', ['event' => $event, 'seats' => $seats]);

        } else {
            return $this->renderPartial('formframe.twig');
        }

    }


    /* При покупке билетов получаем информацию о доступных местах - непроданных билетах */

    public function actionCountseats($seat, $eventid)
    {
        $seat = Yii::$app->request->get('seat');
        $seats = Seats::find()->where(['id' => $seat])->one();
        $tickets = Tickets::find()->where(['seat_id' => $seat])->sum('count');
        $count = $seats->count - $tickets;

        return $this->renderPartial('countseats.twig', ['count' => $count]);
    }


    /* При покупке билетов проверяем есть ли у нас такой польщователь по mail */

    public function actionUserisset($email)
    {
        $mail = Yii::$app->request->get('email');
        $user = Persons::find()->where(['mail' => $mail])->one();

        return $this->renderPartial('userisset.twig', ['user' => $user]);
    }
    

    // Сохранить изменение в форме
    public function actionMark()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

        if (empty($post['tik_id'])) {
            return;
        }

        $ticket = Tickets::find()->where(['id' => $post['tik_id']])->andWhere('status > 0')->one();

        if (empty($ticket)) {
            return;
        }

        $ticket->mark = $post['text'];

        if ($ticket->save()) {
            return ['msg' => 'Сохранено!'];
        }
    }

}