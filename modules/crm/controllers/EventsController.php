<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Band;
use app\models\BandEvent;
use app\models\Biblioevents;
use app\models\BiblioeventSection;
use app\models\Categoryevents;
use app\models\Contests;
use app\models\Newsmakers;
use app\models\NewsmakersEvents;
use app\models\NewsmakerSection;
use app\models\Tools;
use app\models\ToolsEvents;
use app\models\Tour;
use app\models\TourEvent;
use app\models\Companies;
use app\models\Events;
use app\models\Persons; 
use app\models\Seats;
use app\models\Seatings;
use app\models\Tickets;
use app\models\Users;
use app\models\Section;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
{
	/**
	 * @inheritdoc
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

	public $enableCsrfValidation = false;

	public function actionIndex()
	{
		$user = Users::findOne(Yii::$app->user->id);
		$events = Events::getEvents();
		
		$time_now =  date('ymd');

		return $this->render('index.twig', [
			'events' => $events,
			//'summnal' => $summnal,
			//'summbez' => $summbez,
			//'summbiletnik' => $summbiletnik,
			//'summgr' => $summgr,
			'time_now' => $time_now
		]);
	}


	// Прошедшие события
	public function actionPast()
	{
	$user = Users::findOne(Yii::$app->user->id);
        if ($biblioeventid) { // Если есть get id события - выбираем даты только этого события
			$events = Events::find()->joinWith('biblioevents')->where(['event_id' => $biblioeventid])->andwhere('DATE(date) < DATE(NOW())')->orderBy(['date'=>SORT_DESC])->all();
		} else { // Даты всех событий
			$events = Events::find()->joinWith('biblioevents')->andwhere('DATE(date) < DATE(NOW())')->orderBy(['date'=>SORT_DESC])->all();
		}

		$summnal = Tickets::find()->where(['type' => 1])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');
		$summbez = Tickets::find()->where(['type' => 2])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');
		$summbiletnik = Tickets::find()->where(['type' => 3, 'type' => 9])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');
		$summgr = Tickets::find()->where(['type' => 4])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');

		return $this->render('past.twig', [
			'events' => $events,
			'biblioeventid' => $biblioeventid,
			'summnal' => $summnal,
			'summbez' => $summbez,
			'summbiletnik' => $summbiletnik,
			'summgr' => $summgr
		]);
	}


	// Будущие события
	public function actionFuture()
	{
		$user = Users::findOne(Yii::$app->user->id);
		if ($biblioeventid) { // Если есть get id события - выбираем даты только этого события
			$events = Events::find()->joinWith('biblioevents')->where(['event_id' => $biblioeventid])->andwhere('DATE(date) > DATE(NOW())')->orderBy(['date'=>SORT_ASC])->all();
		} else { // Даты всех событий
			$events = Events::find()->joinWith('biblioevents')->andwhere('DATE(date) > DATE(NOW())')->orderBy(['date'=>SORT_ASC])->all();
		}

		$summnal = Tickets::find()->where(['type' => 1])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');
		$summbez = Tickets::find()->where(['type' => 2])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');
		$summbiletnik = Tickets::find()->where(['type' => 3, 'type' => 9])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');
		$summgr = Tickets::find()->where(['type' => 4])->andwhere('DATE(date) = DATE(NOW())')->sum('summa');

		return $this->render('future.twig', [
			'events' => $events,
			'eventsold' => $eventsold ?? null,
			'biblioeventid' => $biblioeventid,
			'summnal' => $summnal,
			'summbez' => $summbez,
			'summbiletnik' => $summbiletnik,
			'summgr' => $summgr
		]);
	}

	public function actionView($id)
	{
		$model = $this->findModel($id);
		$user = Users::findOne(Yii::$app->user->id);

		$biblioevent = Biblioevents::find()->where(['id' => $model->event_id])->one();

		$seats = Seats::find()->where(['event_id' => $model->id])->all();
		$tickets = Tickets::find()->where(['tickets.event_id' => $id])->joinWith('persons')->joinWith('seats')->all();

		return $this->render('view.twig', [
			'model' => $this->findModel($id), 'seats' => $seats, 'tickets' => $tickets
			]);
	}

	public function actionBarcode($id)
	{
		$user = Users::findOne(Yii::$app->user->id);
		$event = Events::find()->where(['events.id' => $id])->joinWith('biblioevents')->one();

		$biblioevent = Biblioevents::find()->where('biblioevents.id = :bid', [':bid' => $event->event_id])
		->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])->one();

		if (empty($biblioevent)) {
			// Проверим доступ к событию в других компаниях
			$biblioevent = Biblioevents::find()->where('biblioevents.id = :id', [':id' => $event->event_id])
			->andWhere(['company_id' => Companies::getIds()])->one();
			if (!empty($biblioevent)) {
				Yii::$app->getSession()->setFlash('success', 'Это событие в другой компании. <br>Телепортация завершена! 🏃🏼‍');
				Companies::setCompany($biblioevent->company_id);
				return $this->redirect(['/crm/events/barcode?id='.$id]);
			} else {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
				return $this->redirect('/site/404');
			}
		}

		$tickets = Tickets::find()->where(['tickets.event_id' => $id])->andWhere(['or', ['tickets.status' => 5], ['tickets.status' => 1, 'tickets.summa' => 0]])->joinWith('persons')->joinWith('seats')->all();
		// echo "<pre>";
		// print_r($tickets);
		// echo "</pre>";

		return $this->render('barcode.twig', [ 'event' => $event, 'tickets' => $tickets]);
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

	/* Ajax даты при выборе события */
	public function actionGetDates($biblioeventid) 
	{
		$dates = Events::find()->where('event_id = :id', [':id' => $biblioeventid])->orderBy(['date' => SORT_DESC])->all();
		return $this->renderPartial('dates.twig', ['dates' => $dates]);
	}

	/* Копируем дату и типы билетов */
	public function actionDuplicate($id)
	{
		$event = $this->findModel($id);

		$newevent = new Events();
		$newevent->event_id = $event->event_id;
		$newevent->place_id = $event->place_id;
		$newevent->city = $event->city;
		$newevent->city_id = $event->city_id;
		$newevent->place = $event->place;
		$newevent->address = $event->address;
		$newevent->artist = $event->artist;
		$newevent->date = $event->date;
		$newevent->button = $event->button;
		$newevent->underbutton = $event->underbutton;
		//$newevent->status = $event->status;
		$newevent->info = $event->info; 

		//echo "<pre>";print_r($newevent);echo "</pre>";

		$newevent->save();

		$seats = Seats::find()->where(['event_id' => $event->id])->asArray()->all();
		foreach ($seats as $seat) {
			$newseat = new Seats();
			$newseat->event_id = $newevent->id;
			$newseat->name = $seat["name"];
			$newseat->count = $seat["count"];
			$newseat->price = $seat["price"];
			$newseat->sec = $seat["sec"];
			$newseat->row = $seat["row"];
			$newseat->promocode = $seat["promocode"];
			$newseat->promolimit = $seat["promolimit"];
			$newseat->info = $seat["info"];
			$newseat->template_id = $seat["template_id"];
			$newseat->nums = $seat["nums"];
			$newseat->css = $seat["css"];

			$newseat->save();
		};

		Yii::$app->getSession()->setFlash('success', 'Событие скопиравано.<a href="/crm/events/update?id='.$newevent->id.'">'.$newevent->id.'</a>');
		//return $this->redirect(['/crm/events/create', 'biblioeventid' => $event->event_id]);
		return $this->redirect(['/crm/events/update', 'id' => $event->id]);
	}





	public function actionCreate($biblioeventid = false)
	{
		
		$user = Users::findOne(Yii::$app->user->id);
		if (!empty($biblioeventid)) {
			$biblioevent = Biblioevents::find()->where(['biblioevents.id' => $biblioeventid])->joinWith('places')->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])->one();
			$biblioevents = NULL;
			if (empty($biblioevent)) {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
				return $this->redirect(['/crm/biblioevents']);
			}
		} else {
			$biblioevent = NULL;
			$biblioevents = Biblioevents::find()->joinWith('places')->where(['biblioevents.company_id' => Companies::getCompanyId()])->orderBy('name')->all();
		}
		
		$all_dates = Events::find()
			->where(['events.event_id' => $biblioevent->id])
			->joinWith('seats')
			->joinWith('places')
			->orderBy(['events.date' => SORT_ASC])->andwhere('DATE(date) > DATE(NOW())') 
			->all();

			// echo "<pre>";
			// print_r($all_dates);
			// echo "</pre>";
			$dates = Null;
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
				
				$t_reg_s = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('summa'); // регистрации сумма

				$t_pay_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->count(); // оплаты кол-во покупок шт
				$t_pay_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('count'); // оплаты кол-во билетов шт
				$t_pay_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('summa'); // оплаты сумма

				$dates[] = array('date' => $date, 's_count' => $s_count, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_c_t' => $t_zayavka_c_t, 't_zayavka_s' => $t_zayavka_s, 't_reg_c' => $t_reg_c,  't_reg_s' => $t_reg_s, 't_pay_c' => $t_pay_c, 't_pay_c_t' => $t_pay_c_t, 't_pay_s' => $t_pay_s, 't_back_c' => $t_back_c, 't_back_s' =>$t_back_s );
			}

		$model = new Events();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще дату'){
				Yii::$app->getSession()->setFlash('success', 'Дата создана.');
				return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Дата создана.');
				return $this->redirect(['/crm/seats/create?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
			}
		} else {
			return $this->render('create.twig', [
				'model' => $model, 'biblioevent' => $biblioevent, 'biblioevents' => $biblioevents, 'dates' => $dates,'user' => $user
				]);
		}
	}

	public function actionAddeventtotour($event_id = false, $tour_id = false)
	{
		$tours = Tour::find()
			->orderBy(['date' => SORT_ASC])->andwhere('DATE(date) > DATE(NOW())') 
			->all();	

		$model = new Events();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще дату'){
				Yii::$app->getSession()->setFlash('success', 'Дата создана.');
				return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Дата создана.');
				return $this->redirect(['/crm/seats/create?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
			}
		} else {
			return $this->render('create.twig', [
				'model' => $model, 'tours' => $tours 
				]);
		}
	}


	public function actionEvents($biblioeventid = false, $edate = false)
	{

		$user = Users::findOne(Yii::$app->user->id);

		if (!empty($biblioeventid)) {
			$biblioevent = Biblioevents::find()->where(['biblioevents.id' => $biblioeventid])->joinWith('places')->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])->one();
			$biblioevents = NULL;
			if (empty($biblioevent)) {
				Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
				return $this->redirect(['/crm/biblioevents']);
			}
		} else {

			$biblioevent = NULL;	
			$biblioevents = Biblioevents::find()->joinWith('places')->where(['biblioevents.company_id' => Companies::getCompanyId()])->orderBy('name')->all();

		 }

		if ($biblioevent){ 
			if ($edate){
				$all_dates = Events::find()
				->where(['events.event_id' => $biblioevent->id])
				->orWhere(['events.abiblioevent_id' => $biblioevent->id])
				->andwhere('DATE(date) >= DATE(:edate)',  [':edate' => $edate] )
				->joinWith('seats')
				->orderBy(['events.date' => SORT_ASC])
				->all();
			} else{
				$all_dates = Events::find()
				->where(['events.event_id' => $biblioevent->id])
				->orWhere(['events.abiblioevent_id' => $biblioevent->id])
				->joinWith('seats')
				->orderBy(['events.date' => SORT_ASC])
				->all();
			}
		}else{
			$all_dates  = Array();	
			foreach ( $biblioevents as $bevent){
				$onedate = Events::find()
				->where(['events.event_id' => $bevent->id])
				->andwhere('DATE(date) >= DATE(:edate)',  [':edate' => $edate] )
				->joinWith('seats')
				->orderBy(['events.date' => SORT_ASC])
				->all();
				$all_dates = array_merge( $all_dates , $onedate);

			}	
				

		}

		$dates = Null; 
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
				
				$t_reg_s = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('summa'); // регистрации сумма

				$t_pay_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->count(); // оплаты кол-во покупок шт
				$t_pay_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('count'); // оплаты кол-во билетов шт
				$t_pay_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('summa'); // оплаты сумма

				$dates[] = array('date' => $date, 's_count' => $s_count, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_c_t' => $t_zayavka_c_t, 't_zayavka_s' => $t_zayavka_s, 't_reg_c' => $t_reg_c, 't_reg_s' => $t_reg_s, 't_pay_c' => $t_pay_c, 't_pay_c_t' => $t_pay_c_t, 't_pay_s' => $t_pay_s, 't_back_c' => $t_back_c, 't_back_s' =>$t_back_s);
			}

		$model = new Events();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще дату'){
				Yii::$app->getSession()->setFlash('success', 'Дата создана.');
				return $this->redirect(['events?biblioeventid='.$biblioevent->id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Дата создана.');
				return $this->redirect(['/crm/seats/events?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
			}
		} else {
			return $this->render('events.twig', [
				'model' => $model, 'biblioevent' => $biblioevent, 'biblioevents' => $biblioevents, 'dates' => $dates, 'user' => $user
				]);
		}
	}


	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$user = Users::findOne(Yii::$app->user->id);

		$biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])
			->andWhere(['biblioevents.company_id' => Companies::getCompanyId()])->one();
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

		$all_dates = Events::find()
			->where(['events.event_id' => $biblioevent->id])
			->joinWith('seats')
			->orderBy(['events.date' => SORT_ASC])->andwhere('DATE(date) >= DATE(NOW())') 
			->all();
		$dates = Null;
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
				
				$t_reg_s = Tickets::find()->where(['event_id' => $date->id, 'summa' => 0, 'status' => 1])->sum('summa'); // регистрации сумма

				$t_pay_c = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->count(); // оплаты кол-во покупок шт
				$t_pay_c_t = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('count'); // оплаты кол-во билетов шт
				$t_pay_s = Tickets::find()->where(['event_id' => $date->id, 'status' => 5])->sum('summa'); // оплаты сумма

				$mybands = BandEvent::find()->where(['event_id' => $date->id])->joinWith('band')->all();
				
				$dates[] = array('date' => $date, 's_count' => $s_count, 't_zayavka_c' => $t_zayavka_c, 't_zayavka_c_t' => $t_zayavka_c_t, 't_zayavka_s' => $t_zayavka_s, 't_reg_c' => $t_reg_c,'t_reg_s' => $t_reg_s, 't_pay_c' => $t_pay_c, 't_pay_c_t' => $t_pay_c_t, 't_pay_s' => $t_pay_s, 'bands' => $mybands);
			}

		$seats = Seats::find()->where('event_id = :event_id', [':event_id' => $model->id])->all();	

		$bands = Band::find()->where(['status' => [1,2]])->all();
		$mybands = BandEvent::find()->where(['event_id' => $model->id])->joinWith('band')->all();

		$tours = Tour::find()->where(['status' => [1,2]])->all();
		$mytours = TourEvent::find()->where(['event_id' => $model->id])->joinWith('tour')->all();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще дату'){
				Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
				return $this->redirect(['create?biblioeventid='.$biblioevent->id]);
			} elseif($_POST['new'] == 'Сохранить и редактировать текущую дату') {				
				Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
				return $this->redirect(['/crm/events/update?id='.$model->id]);
			}else {				
				Yii::$app->getSession()->setFlash('success', 'Дата сохранена.');
				return $this->redirect(['/crm/seats/create?event_id='.$model->id.'&biblioeventid='.$biblioevent->id]);
			} 
		} else {
			return $this->render('create.twig', ['model' => $model, 'biblioevent' => $biblioevent, 'dates' => $dates, 'seats' => $seats, 'bands' => $bands, 'mybands' => $mybands, 'tours' => $tours, 'mytours' => $mytours, 'user' => $user]);
		}
	}

	
	/* Удаляем бенд в событие */
	public function actionBanddel()
	{
		$ids = Biblioevents::EventsIdsAll();
		$id = Yii::$app->request->get('id');
		$band = Yii::$app->request->get('band');

		if (in_array($id, $ids)) {
			$event = Events::findOne($id);
			$b_e = BandEvent::find()->where(['event_id' => $id, 'band_id' => $band])->one();
			if ($b_e->delete()) {
				Yii::$app->getSession()->setFlash('success', 'Бенд удален из этой даты!');
				return $this->redirect(['/crm/events/update?id='.$event->id]);
			}
		}
	}

	

	/* Добавим бенд в событие */
	public function actionBandadd()
	{
		$ids = Biblioevents::EventsIdsAll();
		$id = Yii::$app->request->get('id');
		$band = Yii::$app->request->get('band');

		if (in_array($id, $ids)) {
			$event = Events::findOne($id);
			$b_e = BandEvent::find()->where(['event_id' => $id, 'band_id' => $band])->one();
			if (empty($b_e)) {
				$b_e = new BandEvent();
				$b_e->event_id = $id;
				$b_e->band_id = $band;
				if ($b_e->save()) {
					Yii::$app->getSession()->setFlash('success', 'Раздел добавлен в эту дату!');
					return $this->redirect(['/crm/events/update?id='.$event->id]);
				}
			}
		}
	}

	/* Удаляем тур в событие */
	public function actionTourdel()
	{
		$ids = Biblioevents::EventsIdsAll();
		$id = Yii::$app->request->get('id');
		$tour = Yii::$app->request->get('tour');

		if (in_array($id, $ids)) {
			$event = Events::findOne($id);
			$b_e = TourEvent::find()->where(['event_id' => $id, 'tour_id' => $tour])->one();
			if ($b_e->delete()) {
				Yii::$app->getSession()->setFlash('success', 'Тур удален из этой даты!');
				return $this->redirect(['/crm/events/update?id='.$event->id]);
			}
		}
	}

	public function actionTouradd()
	{
		$ids = Biblioevents::EventsIdsAll();
		$id = Yii::$app->request->get('id');
		$tour = Yii::$app->request->get('tour');

		if (in_array($id, $ids)) {
			$event = Events::findOne($id);
			$b_e = TourEvent::find()->where(['event_id' => $id, 'tour_id' => $tour])->one();
			if (empty($b_e)) {
				$b_e = new TourEvent();
				$b_e->event_id = $id;
				$b_e->tour_id = $tour;
				if ($b_e->save()) {
					Yii::$app->getSession()->setFlash('success', 'Тур добавлен в эту дату!');
					return $this->redirect(['/crm/events/update?id='.$event->id]);
				}
			}
		}
	}




	public function actionClear($id)
	{
		$model = $this->findModel($id);
		$model->place = Null;
		$model->address = Null;
		$model->artist = Null;

		if ($model->save()) {
			Yii::$app->getSession()->setFlash('success', 'Установлено место по-умолчанию.');
			return $this->redirect(['update?id='.$model->id]);
		}
	}





	public function actionFields($id)
	{
		$model = $this->findModel($id);

		$biblioevent = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :id', [':id' => $model->event_id])->one();
		$biblioevents = NULL;
		$dates = Events::find()->where(['event_id' => $biblioevent->id])->orderBy(['events.date' => SORT_ASC])->all();
		$seats = Seats::find()->where('event_id = :event_id', [':event_id' => $model->id])->all();	

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', 'Поля сохранены.');
			return $this->redirect(['fields?id='.$model->id]);
		} else {
			return $this->render('fields.twig', [
				'model' => $model, 'biblioevent' => $biblioevent, 'dates' => $dates, 'seats' => $seats, 'user' => $user
			]);
		}
	}



	public function actionDelete2($id)
	{
		$model = $this->findModel($id);
		
		$model->deleted = 1;
		//echo "<pre>";
		//print_r($model);
		//echo "</pre>";
		if ($model->save()) {
		//$this->findModel($id)->delete(); 
		Yii::$app->getSession()->setFlash('success', 'Удален');
		}
		return $this->redirect(['/crm/events']);
	}



	protected function findModel($id)
	{
		if (($model = Events::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
