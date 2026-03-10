<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Todos;
use app\models\TodoStep;
use app\models\Companies;
use app\models\Events;
use app\models\Newsmakers;
use app\models\NewsmakersEvents;
use app\models\Tickets;
use app\models\Seats;
use app\models\Band;
use app\models\BandEvent;
use app\models\Contests;
use app\models\Persons;
use app\models\Biblioevents;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Users;
date_default_timezone_set('Europe/Moscow');

/**
 * PromoController implements the CRUD actions for Todostep model.
 */
class TodostepController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    public function actionIndex($todo=false)
    {
        if ($todo){
            $query = Todostep::find()->orderBy(['id'=>SORT_ASC])->where('todo_id = :todo', [':todo' => $todo]);
        }
        else{ $query = Todostep::find()->orderBy(['id'=>SORT_ASC]); }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
        $todosteps = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

       
        
        // echo "<pre>";
        // print_r($todosteps);
        // echo "</pre>";

        return $this->render('index.twig', ['todosteps' => $todosteps,'pages' => $pages]);
    }

    public function actionEvent($id=false)
    {
        if ($id){
            $model = Events::findOne($id);

            $user = Users::findOne(Yii::$app->user->id);
            $person = Persons::findOne($user->person_id);

            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                ->one();
            $biblioevents = NULL;
            if (empty($biblioevent)) {
                // Проверим доступ к событию в других компаниях
                $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                    ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
                if (!empty($biblioevent)) {
                    Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                    Companies::setCompany($biblioevent->company_id);
                    return $this->redirect(['/crm/events/update?id='.$id]);
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
                    return $this->redirect('/site/404');
                }
            }
            $newsmakers = Newsmakers::find()
            //->andWhere(['company_id' => Companies::getCompanyId()])
            ->orderBy(['second_name' => SORT_ASC, 'name' => SORT_ASC])->andWhere('status > 0')->all();

            $newsmakersevents = NewsmakersEvents::find()->all();

            // echo "<pre>";
            // print_r($newsmakersevents);
            // echo "</pre>";

            $all_dates = Events::find()
                ->where(['events.event_id' => $biblioevent->id])
                ->joinWith('seats')
                ->orderBy(['events.date' => SORT_ASC])
                ->all();

                foreach ($all_dates as $date) {
                    $s_count = 0;
                    foreach ($date->seats as $seat) {
                        $s_count = $s_count + $seat->count;
                    }
                    $t_zayavka_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->count(); // заявки шт
                    $t_zayavka_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('count'); // заявки шт
                    $t_zayavka_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('summa'); // заявки сумма
                    
                    $t_back_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->count(); // оплаты шт
                    $t_back_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->sum('summa'); // оплаты сумма
                    
                    $t_reg_c = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->count(); // регистрации шт
                    $t_reg_c_t = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('count'); // регистрации шт
                    $t_reg_s = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('summa'); // регистрации сумма

                    $t_pay_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->count(); // оплаты кол-во покупок шт
                    $t_pay_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('count'); // оплаты кол-во билетов шт
                    $t_pay_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('summa'); // оплаты сумма

                    $dates[] = array('date' => $date, 's_count' => $s_count, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_c_t' => $t_zayavka_c_t, 't_zayavka_s' => $t_zayavka_s, 't_reg_c' => $t_reg_c, 't_reg_c_t' => $t_reg_c_t, 't_reg_s' => $t_reg_s, 't_pay_c' => $t_pay_c, 't_pay_c_t' => $t_pay_c_t, 't_pay_s' => $t_pay_s);
                }
                
            $seats = Seats::find()->where('event_id = :event_id', [':event_id' => $model->id])->all();  

            $bands = Band::find()->where(['status' => 1])->all();
            $mybands = BandEvent::find()->where(['event_id' => $model->id])->joinWith('band')->all();


            $contests = Contests::find()->where(['event_id' => $model->id])->all();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if($_POST['new'] == 'Сохранить и создать еще дату'){
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
                } else {                
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['/crm/seats/create?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
                }
            } else {
                return $this->render('promo.twig', ['model' => $model, 'biblioevent' => $biblioevent, 'dates' => $dates, 'seats' => $seats, 'bands' => $bands, 'mybands' => $mybands, 'user' => $user, 'person' => $person, 'newsmakers' => $newsmakers, 'newsmakersevents' => $newsmakersevents, 'contests' => $contests]);
            }
        } else
        {
            $query = Todostep::find()->orderBy(['id'=>SORT_ASC]);
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
            $todostep = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

            $steps = TodoStep::find()->all();
            
            // echo "<pre>";
            // print_r($steps);
            // echo "</pre>";

            return $this->render('index.twig', ['todostep' => $todostep,'pages' => $pages, 'steps' => $steps]);
            } 

    }

    public function actionArtist($id=false)
    {
        if ($id){
            $model = Events::findOne($id);

            $user = Users::findOne(Yii::$app->user->id);
            $person = Persons::findOne($user->person_id);

            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                ->one();
            $biblioevents = NULL;
            if (empty($biblioevent)) {
                // Проверим доступ к событию в других компаниях
                $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                    ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
                if (!empty($biblioevent)) {
                    Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                    Companies::setCompany($biblioevent->company_id);
                    return $this->redirect(['/crm/events/update?id='.$id]);
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
                    return $this->redirect('/site/404');
                }
            }
            $newsmakers = Newsmakers::find()
            //->andWhere(['company_id' => Companies::getCompanyId()])
            ->orderBy(['second_name' => SORT_ASC, 'name' => SORT_ASC])->andWhere('status > 0')->all();

            $newsmakersevents = NewsmakersEvents::find()->all();

            // echo "<pre>";
            // print_r($newsmakersevents);
            // echo "</pre>";

            $all_dates = Events::find()
                ->where(['events.event_id' => $biblioevent->id])
                ->joinWith('seats')
                ->orderBy(['events.date' => SORT_ASC])
                ->all();

                foreach ($all_dates as $date) {
                    $s_count = 0;
                    foreach ($date->seats as $seat) {
                        $s_count = $s_count + $seat->count;
                    }
                    $t_zayavka_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->count(); // заявки шт
                    $t_zayavka_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('count'); // заявки шт
                    $t_zayavka_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('summa'); // заявки сумма
                    
                    $t_back_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->count(); // оплаты шт
                    $t_back_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->sum('summa'); // оплаты сумма
                    
                    $t_reg_c = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->count(); // регистрации шт
                    $t_reg_c_t = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('count'); // регистрации шт
                    $t_reg_s = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('summa'); // регистрации сумма

                    $t_pay_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->count(); // оплаты кол-во покупок шт
                    $t_pay_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('count'); // оплаты кол-во билетов шт
                    $t_pay_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('summa'); // оплаты сумма

                    $dates[] = array('date' => $date, 's_count' => $s_count, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_c_t' => $t_zayavka_c_t, 't_zayavka_s' => $t_zayavka_s, 't_reg_c' => $t_reg_c, 't_reg_c_t' => $t_reg_c_t, 't_reg_s' => $t_reg_s, 't_pay_c' => $t_pay_c, 't_pay_c_t' => $t_pay_c_t, 't_pay_s' => $t_pay_s);
                }
                
            $seats = Seats::find()->where('event_id = :event_id', [':event_id' => $model->id])->all();  

            $bands = Band::find()->where(['status' => 1])->all();
            $mybands = BandEvent::find()->where(['event_id' => $model->id])->joinWith('band')->all();


            $contests = Contests::find()->where(['event_id' => $model->id])->all();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if($_POST['new'] == 'Сохранить и создать еще дату'){
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
                } else {                
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['/crm/seats/create?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
                }
            } else {
                return $this->render('promo-artist.twig', ['model' => $model, 'biblioevent' => $biblioevent, 'dates' => $dates, 'seats' => $seats, 'bands' => $bands, 'mybands' => $mybands, 'user' => $user, 'person' => $person, 'newsmakers' => $newsmakers, 'newsmakersevents' => $newsmakersevents, 'contests' => $contests]);
            }
        } else
        {
            $query = Todostep::find()->orderBy(['id'=>SORT_ASC]);
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
            $todostep = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

            $steps = TodoStep::find()->all();
            
            // echo "<pre>";
            // print_r($steps);
            // echo "</pre>";

            return $this->render('index.twig', ['todostep' => $todostep,'pages' => $pages, 'steps' => $steps]);
            } 

    }

    public function actionPlacer($id=false)
    {
        if ($id){
            $model = Events::findOne($id);

            $user = Users::findOne(Yii::$app->user->id);
            $person = Persons::findOne($user->person_id);

            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                ->one();
            $biblioevents = NULL;
            if (empty($biblioevent)) {
                // Проверим доступ к событию в других компаниях
                $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                    ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
                if (!empty($biblioevent)) {
                    Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                    Companies::setCompany($biblioevent->company_id);
                    return $this->redirect(['/crm/events/update?id='.$id]);
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
                    return $this->redirect('/site/404');
                }
            }
            $newsmakers = Newsmakers::find()
            //->andWhere(['company_id' => Companies::getCompanyId()])
            ->orderBy(['second_name' => SORT_ASC, 'name' => SORT_ASC])->andWhere('status > 0')->all();

            $newsmakersevents = NewsmakersEvents::find()->all();

            // echo "<pre>";
            // print_r($newsmakersevents);
            // echo "</pre>";

            /* Vitalii's changing
                $all_dates = Events::find()
                ->where(['events.event_id' => $biblioevent->id])
                ->joinWith('seats')
                ->orderBy(['events.date' => SORT_ASC])
                ->all();
            */
                $all_dates = Events::find()
                ->where('events.id = :id', [':id' => $id])
                ->joinWith('seats')
                ->orderBy(['events.date' => SORT_ASC])
                ->all();

                foreach ($all_dates as $date) {
                    $s_count = 0;
                    foreach ($date->seats as $seat) {
                        $s_count = $s_count + $seat->count;
                    }
                    $t_zayavka_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->count(); // заявки шт
                    $t_zayavka_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('count'); // заявки шт
                    $t_zayavka_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('summa'); // заявки сумма
                    
                    $t_back_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->count(); // оплаты шт
                    $t_back_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->sum('summa'); // оплаты сумма
                    
                    $t_reg_c = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->count(); // регистрации шт
                    $t_reg_c_t = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('count'); // регистрации шт
                    $t_reg_s = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('summa'); // регистрации сумма

                    $t_pay_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->count(); // оплаты кол-во покупок шт
                    $t_pay_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('count'); // оплаты кол-во билетов шт
                    $t_pay_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('summa'); // оплаты сумма

                    $dates[] = array('date' => $date, 's_count' => $s_count, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_c_t' => $t_zayavka_c_t, 't_zayavka_s' => $t_zayavka_s, 't_reg_c' => $t_reg_c, 't_reg_c_t' => $t_reg_c_t, 't_reg_s' => $t_reg_s, 't_pay_c' => $t_pay_c, 't_pay_c_t' => $t_pay_c_t, 't_pay_s' => $t_pay_s);
                }
                
            $seats = Seats::find()->where('event_id = :event_id', [':event_id' => $model->id])->all();  

            $bands = Band::find()->where(['status' => 1])->all();
            $mybands = BandEvent::find()->where(['event_id' => $model->id])->joinWith('band')->all();


            $contests = Contests::find()->where(['event_id' => $model->id])->all();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if($_POST['new'] == 'Сохранить и создать еще дату'){
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
                } else {                
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['/crm/seats/create?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
                }
            } else {
                return $this->render('promo-placer.twig', ['model' => $model, 'biblioevent' => $biblioevent, 'dates' => $dates, 'seats' => $seats, 'bands' => $bands, 'mybands' => $mybands, 'user' => $user, 'person' => $person, 'newsmakers' => $newsmakers, 'newsmakersevents' => $newsmakersevents, 'contests' => $contests]);
            }
        } else
        {
            return $this->redirect('/site/404');
        } 

    }

    public function actionSmm($id=false)
    {
        if ($id){
            $model = Events::findOne($id);

            $user = Users::findOne(Yii::$app->user->id);
            $person = Persons::findOne($user->person_id);

            $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                ->one();
            $biblioevents = NULL;
            if (empty($biblioevent)) {
                // Проверим доступ к событию в других компаниях
                $biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
                    ->andWhere(['biblioevents.company_id' => Companies::getIds()])->one();
                if (!empty($biblioevent)) {
                    Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
                    Companies::setCompany($biblioevent->company_id);
                    return $this->redirect(['/crm/events/update?id='.$id]);
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
                    return $this->redirect('/site/404');
                }
            }
            $newsmakers = Newsmakers::find()
            //->andWhere(['company_id' => Companies::getCompanyId()])
            ->orderBy(['second_name' => SORT_ASC, 'name' => SORT_ASC])->andWhere('status > 0')->all();

            $newsmakersevents = NewsmakersEvents::find()->all();

            // echo "<pre>";
            // print_r($newsmakersevents);
            // echo "</pre>";

            $all_dates = Events::find()
                ->where(['events.event_id' => $biblioevent->id])
                ->joinWith('seats')
                ->orderBy(['events.date' => SORT_ASC])
                ->all();

                foreach ($all_dates as $date) {
                    $s_count = 0;
                    foreach ($date->seats as $seat) {
                        $s_count = $s_count + $seat->count;
                    }
                    $t_zayavka_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->count(); // заявки шт
                    $t_zayavka_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('count'); // заявки шт
                    $t_zayavka_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 1])->andWhere('summa > 0')->sum('summa'); // заявки сумма
                    
                    $t_back_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->count(); // оплаты шт
                    $t_back_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 7])->sum('summa'); // оплаты сумма
                    
                    $t_reg_c = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->count(); // регистрации шт
                    $t_reg_c_t = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('count'); // регистрации шт
                    $t_reg_s = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('summa'); // регистрации сумма

                    $t_pay_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->count(); // оплаты кол-во покупок шт
                    $t_pay_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('count'); // оплаты кол-во билетов шт
                    $t_pay_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('summa'); // оплаты сумма

                    $dates[] = array('date' => $date, 's_count' => $s_count, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_c_t' => $t_zayavka_c_t, 't_zayavka_s' => $t_zayavka_s, 't_reg_c' => $t_reg_c, 't_reg_c_t' => $t_reg_c_t, 't_reg_s' => $t_reg_s, 't_pay_c' => $t_pay_c, 't_pay_c_t' => $t_pay_c_t, 't_pay_s' => $t_pay_s);
                }
                
            $seats = Seats::find()->where('event_id = :event_id', [':event_id' => $model->id])->all();  

            $bands = Band::find()->where(['status' => 1])->all();
            $mybands = BandEvent::find()->where(['event_id' => $model->id])->joinWith('band')->all();


            $contests = Contests::find()->where(['event_id' => $model->id])->all();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if($_POST['new'] == 'Сохранить и создать еще дату'){
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
                } else {                
                    Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
                    return $this->redirect(['/crm/seats/create?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
                }
            } else {
                return $this->render('promo-smm.twig', ['model' => $model, 'biblioevent' => $biblioevent, 'dates' => $dates, 'seats' => $seats, 'bands' => $bands, 'mybands' => $mybands, 'user' => $user, 'person' => $person, 'newsmakers' => $newsmakers, 'newsmakersevents' => $newsmakersevents, 'contests' => $contests]);
            }
        } else
        {
            $query = Todostep::find()->orderBy(['id'=>SORT_ASC]);
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
            $todostep = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

            $steps = TodoStep::find()->all();
            
            // echo "<pre>";
            // print_r($steps);
            // echo "</pre>";

            return $this->render('index.twig', ['todostep' => $todostep,'pages' => $pages, 'steps' => $steps]);
            } 

    }

    // Пока не нужно, потом выведем сюда полезную инфо
    public function actionView($id)
    {
        $model = Todostep::find()->where(['todo_step.id'=>$id])->one();
        if (empty($model)) {
            Yii::$app->getSession()->setFlash('danger', 'нет такого розыграша.');
            return $this->redirect(['/crm/todostep']);
        }
        return $this->render('view.twig', ['model' => $model]);
    }



    public function actionAdd($id)
    {
        $model = new Todosteptep();
        $model['todo_id'] = $id;
        $model['status'] = 1;
        $user = Users::findOne(Yii::$app->user->id);
        $model['user_id'] = $user['id'];
        

        if ( $model->save()) {
           return $this->redirect(['update', 'id' => $model->todo_id]);
        }

        return $this->render('update', ['model' => $model]);
    } 

    public function actionCreate()
    {
        $model = new Todostep();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Todostep model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Todostep model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) // не использую
    {
       // $this->findModel($id)->delete();

      //  return $this->redirect(['index']);
    }

    /**
     * Finds the Todostep model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Todostep the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Todostep::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
