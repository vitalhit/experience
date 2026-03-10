<?php

namespace app\controllers;

use Yii;
use app\models\Persons;
use app\models\Froms;
use app\models\Visits;
use app\models\Tickets;
use app\models\Events;
use app\models\Goods;
use app\models\Sells;
use app\models\Rents;
use app\models\Companies;
use app\models\CompanyUser;
use app\models\CompanyPerson;
use app\models\Users;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

date_default_timezone_set('Europe/Moscow');

/**
 * ProfileController
 */
class ProfileController extends Controller
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
				'only' => ['index', 'create', 'view', 'update', 'delete', 'statistics'],
				'rules' => [
					[
						'allow' => false,
						'actions' => ['index', 'create', 'view', 'update', 'delete', 'statistics'],
						'roles' => ['?'],
					],
					[
						'allow' => true,
						'actions' => ['index', 'create', 'view', 'update', 'delete', 'statistics'],
						'roles' => ['@'],
					],
				],
			],
		];
	}


	// Профиль
	public function actionIndex()
	{
		// Юзер и его карточка(профиль)
		$user = Users::findOne(Yii::$app->user->id);
		$person = Persons::findOne($user->person_id);
		
		if (empty($person)) {
			return $this->redirect(['/profile/create']);
		}
		
		// Карточки (профили) из всех компаний (потому что у каждой компании своя карточка на этого юзера - мы не передаем данные третьим лицам)
		$persons = Persons::find()->where(['persons.user_id' => $user->id])->all();
		$pids = ArrayHelper::getColumn($persons, 'id');

		$tickets = Tickets::find()->select(['tickets.id', 'tickets.order_id', 'tickets.status', 'tickets.date as order_date', 'tickets.event_id as event_id', 'tickets.status', 'tickets.summa', 'tickets.barcode', 'biblioevents.name', 'events.date as date', 'events.event_id as biblioevent_id', 'cities.id as city_id', 'cities.alias as city',  'seats.name as seat_name', 'seats.sec as seat_sec', 'seats.row as seat_row', 'seats.nums as seat_nums'])
		->where(['user_id' => $pids, 'del' => 0])
		->join('LEFT JOIN', 'events', 'events.id = tickets.event_id')
		->join('LEFT JOIN', 'biblioevents', 'biblioevents.id = events.event_id')
		->join('LEFT JOIN', 'cities', 'cities.id = events.city_id')
		->join('LEFT JOIN', 'seats', 'seats.id = tickets.seat_id')
		->orderBy('order_date DESC, order_id ASC')
		->asArray()->all();

	

		$ticks = null;
		$order_id = $tickets[0]['order_id']??Null;
		$summa = $count = 0;
		$order = $all = [];
		foreach ($tickets as $ticket) {
			if ($ticket['order_id'] == $order_id) {
				$summa += $ticket['summa'];
				$count++;
				$all[] = $ticket;

				$order = [
					'order_id' => $order_id,
					'event_id' => $ticket['event_id'],
					'city' => $ticket['city'],
					'name' => $ticket['name'],
					'date' => $ticket['date'],
					'order_date' => $ticket['order_date'],
					'status' => $ticket['status']
				];
			} else {
				$ticks[] = array('order' => $order, 'summa' => $summa, 'count' => $count, 'tickets' => $all);

				$order_id = $ticket['order_id'];
				$order = [
					'order_id' => $order_id,
					'event_id' => $ticket['event_id'],
					'city' => $ticket['city'],
					'name' => $ticket['name'],
					'date' => $ticket['date'],
					'order_date' => $ticket['order_date'],
					'status' => $ticket['status']
				];

				$summa = $ticket['summa'];
				$count = 1;
				$all = [];
				$all[] = $ticket;
			}
		}
		$ticks[] = array('order' => $order, 'summa' => $summa, 'count' => $count, 'tickets' => $all);

		$rents = Rents::find()->where(['person_id' => $pids])->joinWith('rooms')->all();

		return $this->render('index.twig', ['person' => $person, 'user_id' => $user->id, 'ticks' => $ticks, 'rents' => $rents]);
	}


	public function actionCreate()
	{
		$user = Users::findOne(Yii::$app->user->id);
		$person = Persons::findOne($user->person_id);
		if (isset($person)) {
			Yii::$app->getSession()->setFlash('success', 'Профиль уже заполнен. Управляйте событиями!');
			return $this->redirect(['/biblioevents/my']);
		}

		$model = new Persons();

		if ($model->load(Yii::$app->request->post())) {
			$model->company_id = 0;
			$img = UploadedFile::getInstance($model, 'image');
			if ($img) {
				$model->image = Yii::$app->storage->saveUploadedFile($img);
			}else {
				$model->image = $img;
			}

			$model->save();
			$user->person_id = $model->id;
			$user->save();
			Yii::$app->getSession()->setFlash('success', 'Профиль создан.');
			return $this->redirect(['/profile/index']);
		} else {
			return $this->render('create.twig', ['model' => $model, 'user' => $user]);
		}
	}


	public function actionUpdate()
	{
		// Получаем юзера
		$user = Users::findOne(Yii::$app->user->id);

		// Получаем профиль из persons
		$model = Persons::findOne($user->person_id);

		$image = $model->image;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$img = UploadedFile::getInstance($model, 'image');
			if ($img) {
				$model->image = Yii::$app->storage->saveUploadedFile($img);
			}else {
				$model->image = $image;
			}
			$model->save();
			Yii::$app->getSession()->setFlash('success', 'Профиль сохранен.');
			return $this->redirect(['/profile']);
		} else {
			return $this->render('update.twig', ['model' => $model]);
		}
	}



	public function actionTickets()
	{
		// Получаем юзера
		$user = Users::findOne(Yii::$app->user->id);

		// Получаем профиль из persons
		$person = Persons::findOne($user->person_id);

		if ($person) {
			return $this->render('tickets.twig', ['person' => $person]);
		}
		return $this->redirect(['/login']);
	}

	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionVisits()
	{
		// Получаем юзера
		$user = Users::findOne(Yii::$app->user->id);

		// Получаем профиль из persons
		$person = Persons::findOne($user->person_id);

		if ($person) {
			return $this->render('visits.twig', ['person' => $person]);
		}
		return $this->redirect(['/login']);
	}


	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionBonuses()
	{
		// Получаем юзера
		$user = Users::findOne(Yii::$app->user->id);

		// Получаем профиль из persons
		$person = Persons::findOne($user->person_id);

		if ($person) {
			return $this->render('bonuses.twig', ['person' => $person]);
		}
		return $this->redirect(['/login']);
	}


}
