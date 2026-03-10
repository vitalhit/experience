<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Ads;
use app\models\AuthAssignment;
use app\models\Biblioevents;
use app\models\BiblioeventSection;
use app\models\Categoryevents;
use app\models\Companies;
use app\models\CompanyUser;
use app\models\Contract;
use app\models\Promoters;
use app\models\Events;
use app\models\EventFinance;
use app\models\Img;
use app\models\Persons;
use app\models\PictureForm;
use yii\data\Pagination;
use app\models\Section;
use app\models\Seatings;
use app\models\Secretcode;
use app\models\Tickets;
use app\models\Users;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * BiblioeventsController implements the CRUD actions for Biblioevents model.
 */
class BiblioeventsController extends Controller
{


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'view', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        if (empty(Companies::getCompany())) {
            Yii::$app->getSession()->setFlash('danger', 'Выберите компанию!');
            return $this->redirect(['/crm/company/my']);
        }
        // Если нет персоны - редирект на создание
        if (empty(Persons::isPerson())) {
            Yii::$app->getSession()->setFlash('danger', 'Заполните профиль!');
            return $this->redirect(['/profile/create']);
        }

        $biblioevents = Biblioevents::find()
        ->where(['company_id' => Companies::getCompanyId()])
        ->joinWith(['events' => function ($query) {
            $query->onCondition('DATE(events.date) >= DATE(NOW())');
        }])
        ->orderBy('id')
        ->all();


        foreach ($biblioevents as $biblio) {

            $all_dates = Events::find()
            ->where(['events.event_id' => $biblio->id])
            ->joinWith('seats')
            ->orderBy(['events.date' => SORT_ASC])
            ->all();

            $tic = $t_zayavka_c = $t_zayavka_s = $t_back_c = $t_back_s = $t_pay_c = $t_pay_s = 0;
            foreach ($biblio->events as $date) {
                $tic = $tic + Tickets::find()->where(['event_id' => $date->id, 'tickets.del' => 0])->sum('count');
                $t_zayavka_c = $t_zayavka_c + Tickets::find()->where(['event_id' => $date->id, 'tickets.del' => 0])->andWhere('status = 1')->count(); // заявки шт
                $t_zayavka_s = $t_zayavka_s + Tickets::find()->where(['event_id' => $date->id, 'tickets.del' => 0])->andWhere('status = 1')->sum('summa'); // заявки сумма
                $t_back_c = $t_back_c + Tickets::find()->where(['event_id' => $date->id, 'tickets.del' => 0])->andWhere('status = 7')->count(); // возвраты шт
                $t_back_s = $t_back_s + Tickets::find()->where(['event_id' => $date->id, 'tickets.del' => 0])->andWhere('status = 7')->sum('summa'); // возвраты сумма
                $t_pay_c = $t_pay_c + Tickets::find()->where(['event_id' => $date->id, 'tickets.del' => 0])->andWhere('status = 5')->count(); // оплаты шт
                $t_pay_s = $t_pay_s + Tickets::find()->where(['event_id' => $date->id, 'tickets.del' => 0])->andWhere('status = 5')->sum('summa'); // оплаты сумма
            }
            $biblioev[] = array('biblioevent' => $biblio, 'tickets' => $tic, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_s' => $t_zayavka_s, 't_pay_c' => $t_pay_c, 't_pay_s' => $t_pay_s);
        }
        // $tt = Tickets::find()->where(['event_id' => 1657, 'tickets.del' => 0, 'status' => 5])->count();
        // echo $tt;

        // echo "<pre>";
        // print_r($tt);
        // echo "</pre>";

        return $this->render('index.twig', ['biblioev' => $biblioev??Null]);
    }


    // шаблон для теста, Виталий разбирается с выгрузкой большого количества билетов 05.10.2022
    public function actionTickets($id, $event_id = false)
    {

        $user = Users::findOne(Yii::$app->user->id);
        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->joinWith('categoryevents')
        ->joinWith('places')
        ->joinWith(['letterBuy' => function ($query) {
            $query->onCondition('letters.type = 1');
        }])
        ->one();

        if (empty($biblioevent)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/report?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!. ');
                return $this->redirect('/site/404');
            }
        }

        $dates = Events::find()
        ->where(['event_id' => $biblioevent->id])
        ->orderBy(['date' => SORT_DESC])
        ->all();

        $dates_ids = ArrayHelper::getColumn($dates, 'id');

        $year = 2022;

        if (!empty($event_id)) {
            $tickets = Tickets::find()->where(['event_id' => $event_id, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all();
        } else {
            $tickets = Tickets::find()->where(['event_id' => $dates_ids, 'tickets.del' => 0])->andWhere('tickets.status > 0 AND DATE(tickets.date) >= DATE("' . $year . '-01-01") AND DATE(tickets.date) < DATE("' . ($year + 1) . '-01-01") ')->orderBy(['date' => SORT_DESC])->all();
        }


        return $this->render('view.twig', ['biblioevent' => $biblioevent,
            'user' => $user,
            'dates' => $dates,
            'event_id' => $event_id,
            'tickets' => $tickets,

        ]);
    }


    public function actionView($id, $event_id = false, $page = 1)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $offset = ($page - 1) * 1000;

        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->joinWith('categoryevents')
        ->joinWith('places')
        ->joinWith(['letterBuy' => function ($query) {
          $query->onCondition('letters.type = 1');
      }])
        ->one();

        if (empty($biblioevent)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/report?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!. ');
                return $this->redirect('/site/404');
            }
        }

        $dates = Events::find()
        ->where(['event_id' => $biblioevent->id])
        ->orderBy(['date' => SORT_DESC])
        ->all();

        $dates_ids = ArrayHelper::getColumn($dates, 'id');

        $alleventfinances = null;

        $eventdeal_profit = 0;
        $eventdeal_tickets = 0;

        if (!empty($event_id)) {
            $dates_ids = $event_id;

            $fin = EventFinance::find()
            ->where(['event_finance.event_id' => $event_id])
            ->andWhere('event_finance.status > 0')
            ->joinWith('personf')
            ->joinWith('contragent')
            ->orderBy('event_finance.date_create desc')
            ->all();
            $event = Events::find()->where(['id' => $event_id])->one();

            if (!empty($fin)) {
                $alleventfinances[] = array('event' => $event, 'finance' => $fin);
            }
        }

        $tickets = Tickets::find()
        ->where(['tickets.event_id' => $dates_ids, 'tickets.del' => 0])
        ->andWhere('tickets.status > 0')
        ->joinWith(['persons', 'events', 'seats'])
        ->orderBy(['tickets.date' => SORT_DESC])
        ->limit(1000)->offset($offset)
        ->all();

        $count_pages = ceil(Tickets::find()->where(['event_id' => $dates_ids, 'tickets.del' => 0])->andWhere('tickets.status > 0')->count() / 1000);

        // Посчитаем итог
        $ticket_pay_sum = 0;
        $ticket_pay_count = 0;
        $ticket_nopay_sum = 0;
        $ticket_nopay_count = 0;
        $ticket_back_sum = 0;
        $ticket_back_count = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->status == 5 or ($ticket->status == 1 and $ticket->summa == 0)) {
                $ticket_pay_sum = $ticket_pay_sum + $ticket->summa;
                $ticket_pay_count++;
            } elseif ($ticket->status == 7) {
                $ticket_back_sum = $ticket_back_sum + $ticket->summa;
                $ticket_back_count++;
            } else {
                $ticket_nopay_sum = $ticket_nopay_sum + $ticket->summa;
                $ticket_nopay_count++;
            }
        }
        $company = Companies::getCompany();
        $contract = Contract::findOne(['global_id' => $company->global_id]);

        return $this->render('view.twig', [
            'biblioevent' => $biblioevent,
            'user' => $user,
            'page' => $page,
            'count_pages' => $count_pages,
            'dates' => $dates,
            'event_id' => $event_id,
            'tickets' => $tickets,
            'ticket_pay_sum' => $ticket_pay_sum,
            'ticket_pay_count' => $ticket_pay_count,
            'ticket_back_sum' => $ticket_back_sum,
            'ticket_back_count' => $ticket_back_count,
            'ticket_nopay_sum' => $ticket_nopay_sum,
            'ticket_nopay_count' => $ticket_nopay_count,
            'alleventfinances' => $alleventfinances,
            'contract' => $contract,
            'eventdeal_profit' => $eventdeal_profit,
            'eventdeal_tickets' => $eventdeal_tickets,
        ]);
    }


    public function actionViewpdf($id, $event_id = false)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->joinWith('categoryevents')
        ->joinWith('places')
        ->joinWith(['letterBuy' => function ($query) {
            $query->onCondition('letters.type = 1');
        }])
        ->one();

        if (empty($biblioevent)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/report?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!. ');
                return $this->redirect('/site/404');
            }
        }

        $dates = Events::find()
        ->where(['event_id' => $biblioevent->id])
        ->orderBy(['date' => SORT_DESC])
        ->all();

        $dates_ids = ArrayHelper::getColumn($dates, 'id');

        if (!empty($event_id)) {
            $tickets = Tickets::find()->where(['event_id' => $event_id, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all();
        } else {
            $tickets = Tickets::find()->where(['event_id' => $dates_ids, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all();
        }

        // Посчитаем итог
        $ticket_pay_sum = 0;
        $ticket_pay_count = 0;
        $ticket_nopay_sum = 0;
        $ticket_nopay_count = 0;
        $ticket_back_sum = 0;
        $ticket_back_count = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->status == 5 or ($ticket->status == 1 and $ticket->summa == 0)) {
                $ticket_pay_sum = $ticket_pay_sum + $ticket->summa;
                $ticket_pay_count++;
            } elseif ($ticket->status == 7) {
                $ticket_back_sum = $ticket_back_sum + $ticket->summa;
                $ticket_back_count++;
            } else {
                $ticket_nopay_sum = $ticket_nopay_sum + $ticket->summa;
                $ticket_nopay_count++;
            }

        }
        $content = $this->renderPartial('viewpdf.twig', ['biblioevent' => $biblioevent,
            'user' => $user,
            'dates' => $dates,
            'event_id' => $event_id,
            'tickets' => $tickets,
            'ticket_pay_sum' => $ticket_pay_sum,
            'ticket_pay_count' => $ticket_pay_count,
            'ticket_back_sum' => $ticket_back_sum,
            'ticket_back_count' => $ticket_back_count,
            'ticket_nopay_sum' => $ticket_nopay_sum,
            'ticket_nopay_count' => $ticket_nopay_count
        ]);


        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/bootstrap.css',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/akt.css',
            // set mPDF properties on the fly
            'options' => ['title' => 'Отчет за событие'],

        ]);
        $pdf->getApi()->addPage();

        // return the pdf output as per the destination setting
        return $pdf->render();


    }


    public function actionViewcsv($id, $event_id = false)
    {
        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->joinWith('categoryevents')
        ->joinWith('places')
        ->joinWith(['letterBuy' => function ($query) {
            $query->onCondition('letters.type = 1');
        }])
        ->one();

        if (empty($biblioevent)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/report?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!');
                return $this->redirect('/site/404');
            }
        }

        $dates = Events::find()
        ->where(['event_id' => $biblioevent->id])
        ->orderBy(['date' => SORT_DESC])
        ->all();

        $dates_ids = ArrayHelper::getColumn($dates, 'id');

        if (!empty($event_id)) {
            $tickets = Tickets::find()->where(['event_id' => $event_id, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all();
            $date = Events::find()->where(['id' => $event_id])->one();
        } else {
            $tickets = Tickets::find()->where(['event_id' => $dates_ids, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all();
        }

        // Посчитаем итог
        $ticket_pay_sum = 0;
        $ticket_pay_count = 0;
        $ticket_nopay_sum = 0;
        $ticket_nopay_count = 0;
        $ticket_back_sum = 0;
        $ticket_back_count = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->status == 5 or ($ticket->status == 1 and $ticket->summa == 0)) {
                $ticket_pay_sum = $ticket_pay_sum + $ticket->summa;
                $ticket_pay_count++;
            } elseif ($ticket->status == 7) {
                $ticket_back_sum = $ticket_back_sum + $ticket->summa;
                $ticket_back_count++;
            } else {
                $ticket_nopay_sum = $ticket_nopay_sum + $ticket->summa;
                $ticket_nopay_count++;
            }

        }
        

        $for_file = $this->renderPartial('viewcsv.twig', [
            'biblioevent' => $biblioevent,
            'dates' => $dates,
            'event_id' => $event_id,
            'tickets' => $tickets,
            'ticket_pay_sum' => $ticket_pay_sum,
            'ticket_pay_count' => $ticket_pay_count,
            'ticket_back_sum' => $ticket_back_sum,
            'ticket_back_count' => $ticket_back_count,
            'ticket_nopay_sum' => $ticket_nopay_sum,
            'ticket_nopay_count' => $ticket_nopay_count

        ]);

        file_put_contents('viewcsv.csv', $date->date.";Имя;Фамилия;Штрих-код;Название билета;Стоимость;Промокод;Почта;Телефон\n" . $for_file);

        return $this->redirect(['/viewcsv.csv']);



    }

    // временный скрипт для добавления в таблице tickets столбика biblioevent_id
    public function actionUpdatetickets()
    {
    
        // Получаем все тикеты где biblioevent_id is NULL
        $tickets = Tickets::find()
            ->where(['biblioevent_id' => null])
            ->limit(2500)
            ->all();
        
        $updatedCount = 0;
        
        
        foreach ($tickets as $ticket) {
            echo '<br>event_id:'.$ticket->event_id.''; 

            if (!empty($ticket->event_id)) {
                
                echo ' — event_id:'.$ticket->event_id.'';                 
                // Находим соответствующее событие
                

                $event = Events::findOne($ticket->event_id);
                
                if ($event && !empty($event->biblioevent_id)) {
                    // Обновляем ticket
                    $ticket->biblioevent_id = $event->biblioevent_id;
                    
                    if ($ticket->save()) {
                        $updatedCount++;
                    } 

                }

            }
        }    
         echo 'success '.time().' $updatedCount = '. $updatedCount;  
}

    public function actionViewcsvall($id, $event_id = false)
    {
        
        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->one();



        if ( !empty($biblioevent) and empty($event_id) ){
            $tickets = Tickets::find()->where(['biblioevent_id' => $biblioevent->id, 'tickets.del' => 0])->andWhere('tickets.status > 0')->onCondition('DATE(tickets.date) >= DATE(NOW()  - INTERVAL 5 MONTH )')->orderBy(['date' => SORT_DESC])->all(); 

        }elseif ( !empty($event_id) ){
            $tickets = Tickets::find()->where(['event_id' => $event_id, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all(); 
            
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Выберите компанию!');
            return $this->redirect(['/crm/company/my']);
        }
        
        $for_file = $this->renderPartial('viewcsvall.twig', [
            'tickets' => $tickets
        ]);

        //   echo "<pre>";
        // print_r($tickets);
        // echo "</pre>";die;


        file_put_contents('viewcsvall.csv', "Имя;Фамилия;Штрих-код;Название билета;Стоимость;Промокод;Почта;Телефон;utm_source;utm_campaign;utm_medium;utm_content;utm_term\n" . $for_file);

        return $this->redirect(['/viewcsvall.csv']);


    }

    public function actionBarcode($id, $event_id = false)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->joinWith('categoryevents')
        ->joinWith('places')
        ->joinWith(['letterBuy' => function ($query) {
            $query->onCondition('letters.type = 1');
        }])
        ->one();

        if (empty($biblioevent)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/report?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!!!');
                return $this->redirect('/site/404');
            }
        }

        $dates = Events::find()
        ->where(['event_id' => $biblioevent->id])
        ->orderBy(['date' => SORT_DESC])
        ->all();

        $dates_ids = ArrayHelper::getColumn($dates, 'id');

        if (!empty($event_id)) {
            $tickets = Tickets::find()->where(['event_id' => $event_id, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all();
        } else {
            $tickets = Tickets::find()->where(['event_id' => $dates_ids, 'tickets.del' => 0])->andWhere('tickets.status > 0')->orderBy(['date' => SORT_DESC])->all();
        }


        return $this->render('barcode.twig', ['biblioevent' => $biblioevent, 'user' => $user, 'dates' => $dates, 'event_id' => $event_id, 'tickets' => $tickets]);
    }


    public function actionBrons($id, $event_id = false)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->joinWith('categoryevents')
        ->joinWith('places')
        ->one();

        if (empty($biblioevent)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/brons?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!!!!');
                return $this->redirect('/site/404');
            }
        }

        $dates = Events::find()
        ->where(['event_id' => $biblioevent->id])
        ->orderBy(['date' => SORT_DESC])
        ->all();

        $dates_ids = ArrayHelper::getColumn($dates, 'id');

        if (!empty($event_id)) {
            $tickets = Tickets::find()->where(['event_id' => $event_id, 'tickets.del' => 0, 'tickets.status' => [0, 1]])->andWhere('tickets.summa > 0')
            ->orderBy(['date' => SORT_DESC])->all();
        } else {
            $tickets = Tickets::find()->where(['event_id' => $dates_ids, 'tickets.del' => 0, 'tickets.status' => [0, 1]])->andWhere('tickets.summa > 0')
            ->orderBy(['date' => SORT_DESC])->all();
        }


        // Посчитаем итог
        $ticket_bron_sum = 0;
        $ticket_bron_count = 0;
        $ticket_nobron_sum = 0;
        $ticket_nobron_count = 0;
        foreach ($tickets as $ticket) {
            if ($ticket->status == 1) {
                $ticket_bron_sum = $ticket_bron_sum + $ticket->summa;
                $ticket_bron_count++;
            } else {
                $ticket_nobron_sum = $ticket_nobron_sum + $ticket->summa;
                $ticket_nobron_count++;
            }

        }

        // echo "<pre>";
        // print_r($tickets);
        // echo "</pre>";

        return $this->render('brons.twig', ['biblioevent' => $biblioevent,
            'user' => $user,
            'dates' => $dates,
            'event_id' => $event_id,
            'tickets' => $tickets,
            'ticket_bron_sum' => $ticket_bron_sum,
            'ticket_bron_count' => $ticket_bron_count,
            'ticket_nobron_sum' => $ticket_nobron_sum,
            'ticket_nobron_count' => $ticket_nobron_count
        ]);
    }


    public function actionAlltickets()
    {
        $user = Users::findOne(Yii::$app->user->id);
        $company_id = Companies::getCompanyId();

        $tickets = Tickets::find()
        ->select(['tickets.id', 'tickets.user_id', 'tickets.date', 'tickets.seat', 'tickets.money', 'tickets.barcode', 'tickets.status', 'tickets.status_come', 'tickets.name', 'tickets.secondname', 'tickets.email', 'tickets.phone', 'tickets.order_id', 'biblioevent.name as biblioevent_name', 'event.date as event_date'])
        ->where(['tickets.company_id' => $company_id, 'tickets.del' => 0])->andWhere('tickets.status > 0')
        ->join('LEFT JOIN', 'biblioevents as biblioevent', 'biblioevent.id = tickets.biblioevent_id')
        ->join('LEFT JOIN', 'events as event', 'event.id = tickets.event_id')
        ->orderBy(['date' => SORT_DESC])->asArray()->all();

        return $this->render('alltickets.twig', ['tickets' => $tickets]);
    }

    // Меняем статус билету если гость пришел. 0 - нет статуса, 1 - пришел, 2 - НЕ пришел
    public function actionComein($ticketid, $biblioeventid)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $ticket = Tickets::findOne($ticketid);
        if ($ticket->status_come != 1) { // Если еще не отмечалось или он не пришел - то меняем на ПРИШЕЛ
            $ticket->status_come = 1;
            if ($ticket->save()) {
                return 'Пришел';
            }
        } elseif ($ticket->status_come == 1) { // Если пришел - то меняем на НЕ ПРИШЕЛ
            $ticket->status_come = 2;
            if ($ticket->save()) {
                return 'Не пришел';
            }
        };
    }


    public function actionInstruction($id)
    {

        $user = Users::findOne(Yii::$app->user->id);

        $biblioevent = Biblioevents::find()
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])
        ->joinWith('categoryevents')
        ->one();

        return $this->render('instruction.twig', ['biblioevent' => $biblioevent, 'user' => $user]);
    }

    public function actionSecretcode($id)
    {
        $user = Users::findOne(Yii::$app->user->id);

        $biblioevent = Biblioevents::find()->where('biblioevents.id = :bid', [':bid' => $id])->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])->joinWith('categoryevents')->one();

        // echo "<pre>";
        // print_r($biblioevent);
        // echo "</pre>";die;

        $query = Secretcode::find()->orderBy('id desc')->where('secretcode.biblioevent_id = :bid', [':bid' => $id]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'PageSize' => 1000]);
        $secretcodes = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        return $this->render('secretcode.twig', ['biblioevent' => $biblioevent, 'secretcodes' => $secretcodes,'user' => $user,'pages' => $pages]);
    }
    
    public function actionSecretcodecreate($biblioevent_id)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $model = new secretcode();

        if ($model->load(Yii::$app->request->post())) {

            if (empty($model->title)){ 
                $model->title = "Используете при покупке билета секретное слово".$model->code ."и вы получите скидку ".$model->percent."%" ;
            }


            $model->save(); 
            return $this->redirect(['/crm/biblioevents/secretcode', 'id' => $biblioevent_id]);
        }

        return $this->render('secretcode_create', [
            'model' => $model, 'biblioevent_id' => $biblioevent_id
        ]);
    }

     public function actionSecretcodeupdate($id)
    {
        
        $user = Users::findOne(Yii::$app->user->id);
        $model = Secretcode::find()->where('secretcode.id = :bid', [':bid' => $id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/crm/biblioevents/secretcode', 'id' => $model->biblioevent_id]);
        }

        return $this->render('secretcode_update', [
            'model' => $model,
        ]);
    }


    public function actionCreate()
    {
        $company = Companies::getCompany();
        $user = Users::findOne(Yii::$app->user->id);

        $category = ArrayHelper::map(CategoryEvents::find()->where('status>0')->asArray()->all(), 'id', 'category', 'type');
        /*$category = ArrayHelper::map(CategoryEvents::find()->where(['company_id' => $company->id])->asArray()->all(), 'id', 'category');
        if (empty($category)) {
            Yii::$app->getSession()->setFlash('success', 'Сначала создайте категорию.');
            return $this->redirect(['/crm/categoryevents/create']);
        }*/// 1 августа 19:00 больше не нужно использовать катеогрии

        if (AuthAssignment::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['item_name' => 'admin'])->one()) {
            $ads = 1;
        } else {
            $ads = 0;
        }

        $model = new Biblioevents();

        if ($model->load(Yii::$app->request->post())) {
            $img = UploadedFile::getInstance($model, 'image');
            if (!empty($img)) {
                $model->image = Yii::$app->storage->saveImgFile($img);
            }
            $model->save();

            Yii::$app->getSession()->setFlash('success', 'Событие создано.');
            return $this->redirect(['/crm/events/create/?biblioeventid=' . $model->id]);
        } else {
            return $this->render('create.twig', ['model' => $model, 'category' => $category, 'company' => $company, 'ads' => $ads, 'user' => $user]);
        }
    }


    public function actionUpdate($id)
    {

        $user = Users::findOne(Yii::$app->user->id);

        if (AuthAssignment::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['item_name' => 'admin'])->one()) {
            $ads = 1;
        } else {
            $ads = 0;
        }

        $category = ArrayHelper::map(CategoryEvents::find()->where('status>0')->asArray()->all(), 'id', 'category', 'type');
        $model = Biblioevents::find()
        ->joinWith('cities')
        ->joinWith('sectionone')
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['company_id' => Companies::getCompanyId()])
        ->one();

        if (empty($model)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/update?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!!!!!!!');
                return $this->redirect('/site/404');
            }
        }

        $events = Events::find()->where(['event_id' => $model->id])->all();
        $old_image = $model->image;

        $sections = Section::find()->all();
        $mysections = BiblioeventSection::find()->where(['biblioevent_id' => $model->id])->joinWith('section')->all();

        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";

        if ($model->load(Yii::$app->request->post())) {


            $done = Img::New($model);

            if (!empty($done)) {
                $model->image = $done;
            } else {
                $model->image = $old_image;
            }
            $model->save();
            Ads::ReAds($model);


            // 	$yes_bib = Ads::Explo($model->a_p_bib);
            //       $no_bib = Ads::Explo($model->a_m_bib);
            //       array_push($no_bib, $model->id);
            //       $bibs = array_diff($yes_bib, $no_bib);

            //       echo "<pre>";
            // print_r($bibs);
            // echo "</pre>";


            Yii::$app->getSession()->setFlash('success', 'Событие сохранено.');
            return $this->redirect(['/crm/events/create', 'biblioeventid' => $model->id]);
        }
        return $this->render('update.twig', ['model' => $model, 'events' => $events, 'category' => $category ?? Null, 'sections' => $sections, 'mysections' => $mysections, 'ads' => $ads ?? Null , 'user' => $user]);
    }


    public function actionButton($id)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $category = ArrayHelper::map(CategoryEvents::find()->where(['company_id' => Companies::getCompanyId()])->asArray()->all(), 'id', 'category');
        $model = Biblioevents::find()
        ->joinWith('cities')
        ->joinWith('sectionone')
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['company_id' => Companies::getCompanyId()])
        ->one();

        if (empty($model)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/update?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события...');
                return $this->redirect('/site/404');
            }
        }

        $events = Events::find()->where(['event_id' => $model->id])->all();
        $image = $model->image;

        $sections = Section::find()->all();
        $mysections = BiblioeventSection::find()->where(['biblioevent_id' => $model->id])->joinWith('section')->all();


        // echo "<pre>";
        // print_r($model);
        // echo "</pre>";

        if ($model->load(Yii::$app->request->post())) {

            $img = UploadedFile::getInstance($model, 'image');
            if ($img) {
                $model->image = Yii::$app->storage->saveImgFile($img);
            } else {
                $model->image = $image;
            }
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Событие сохранено.');
            return $this->redirect(['/crm/events/create', 'biblioeventid' => $model->id]);
        }
        return $this->render('button.twig', ['model' => $model, 'events' => $events, 'category' => $category, 'sections' => $sections, 'mysections' => $mysections]);
    }

    public function actionButton2($id)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $category = ArrayHelper::map(CategoryEvents::find()->where(['company_id' => Companies::getCompanyId()])->asArray()->all(), 'id', 'category');
        $model = Biblioevents::find()
        ->joinWith('cities')
        ->joinWith('sectionone')
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['company_id' => Companies::getCompanyId()])
        ->one();

        if (empty($model)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/update?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события..');
                return $this->redirect('/site/404');
            }
        }

        $events = Events::find()->where(['event_id' => $model->id])->all();
        $image = $model->image;

        $sections = Section::find()->all();
        $mysections = BiblioeventSection::find()->where(['biblioevent_id' => $model->id])->joinWith('section')->all();


        // echo "<pre>";
        // print_r($model);
        // echo "</pre>";

        if ($model->load(Yii::$app->request->post())) {

            $img = UploadedFile::getInstance($model, 'image');
            if ($img) {
                $model->image = Yii::$app->storage->saveImgFile($img);
            } else {
                $model->image = $image;
            }
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Событие сохранено.');
            return $this->redirect(['/crm/events/create', 'biblioeventid' => $model->id]);
        }
        return $this->render('button2.twig', ['model' => $model, 'events' => $events, 'category' => $category, 'sections' => $sections, 'mysections' => $mysections]);
    }


    public function actionFormsettings($id)
    {
        $user = Users::findOne(Yii::$app->user->id);
        if (AuthAssignment::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['item_name' => 'admin'])->one()) {
            $ads = 1;
        } else {
            $ads = 0;
        }


        $model = Biblioevents::find()
        ->joinWith('cities')
        ->joinWith('sectionone')
        ->where('biblioevents.id = :bid', [':bid' => $id])
        ->andWhere(['company_id' => Companies::getCompanyId()])
        ->one();

        if (empty($model)) {
            // Проверим доступ к событию в других компаниях
            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $id])
            ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
            if (!empty($biblioevent)) {
                Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                Companies::setCompany($biblioevent->company_id);
                return $this->redirect(['/crm/biblioevents/update?id=' . $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события!!!!!!!');
                return $this->redirect('/site/404');
            }
        }

        $events = Events::find()->where(['event_id' => $model->id])->all();


        $sections = Section::find()->all();
        $mysections = BiblioeventSection::find()->where(['biblioevent_id' => $model->id])->joinWith('section')->all();

        // echo "<pre>";
        // print_r($model);
        // echo "</pre>";


        return $this->render('formsettings.twig', ['model' => $model, 'events' => $events, 'category' => $category ?? null, 'sections' => $sections, 'mysections' => $mysections, 'ads' => $ads]);
    }


    /* При создании события проверяем уникальность алиаса */
    public function actionAliasisset($alias, $city, $biblioeventid = false)
    {
        $user = Users::findOne(Yii::$app->user->id);
        $alias = Biblioevents::find()->where(['alias' => $alias])->andWhere(['city' => $city])->one();
        if (!empty($biblioeventid) && $alias->id == $biblioeventid) {
            return;
        } else {
            return $alias->id;
        }
    }




    /* Удаляем раздел в событии */
    public function actionSecdel()
    {
        $id = Yii::$app->request->get('id');
        $sec = Yii::$app->request->get('sec');

        $biblioevent = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->andWhere(['biblioevents.id' => $id])->one();
        $bib_sec = BiblioeventSection::find()->where(['biblioevent_id' => $biblioevent->id])->andWhere(['section_id' => $sec])->one();
        $bib_sec->delete();

        Yii::$app->getSession()->setFlash('success', 'Раздел удален из этого события!');
        return $this->redirect(['/crm/biblioevents/update?id=' . $id]);
    }


    /* Добавим раздел в событие */
    public function actionSecadd()
    {
        $id = Yii::$app->request->get('id');
        $sec = Yii::$app->request->get('sec');

        $biblioevent = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->andWhere(['biblioevents.id' => $id])->one();
        $bib_sec = BiblioeventSection::find()->where(['biblioevent_id' => $biblioevent->id])->andWhere(['section_id' => $sec])->one();
        if (empty($bib_sec)) {
            $new_bib_sec = new BiblioeventSection();
            $new_bib_sec->biblioevent_id = $id;
            $new_bib_sec->section_id = $sec;
            if ($new_bib_sec->save()) {
                Yii::$app->getSession()->setFlash('success', 'Раздел добавлен в это событие!');
            }
        }

        // echo "<pre>";
        // print_r($bib_sec);
        // echo "</pre>";

        // die;

        return $this->redirect(['/crm/biblioevents/update?id=' . $id]);
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->del = 1;
        $model->save();

        //$this->findModel($id)->delete();


        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Biblioevents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
