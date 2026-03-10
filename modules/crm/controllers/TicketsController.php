<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Tickets;
use app\models\Seats;
use app\models\Smena;
use app\models\Biblioevents;
use app\models\Events;
use app\models\Persons;
use app\models\Companies;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * TicketsController implements the CRUD actions for Tickets model.
 */
class TicketsController extends Controller
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

	public function actionMonthdate()
	{
		$date = '2018-02-03';

		$tickets = Tickets::find()->joinWith('persons')->joinWith('events')->where(['DATE(tickets.date)' => $date])->orderBy(['tickets.event_id' => SORT_DESC])->all();
		
		$biblioevents = Tickets::find()->joinWith('events')->where(['DATE(tickets.date)' => $date])->groupBy(['events.event_id'])->all();
		
		$tickets_sum = Tickets::findBySql('SELECT
			SUM(CASE WHEN type = 1 THEN summa ELSE 0 END) as nal,
			SUM(CASE WHEN type = 2 THEN summa ELSE 0 END) as beznal,
			SUM(CASE WHEN type = 9 THEN summa ELSE 0 END) as yandex,
			SUM(summa) as summa
			FROM tickets WHERE MONTH(tickets.date) = MONTH(now())')->asArray()->all();


		$tickets_count = count($tickets);
		$word = Tickets::pluralForm($tickets_count, 'билет', 'билета', 'билетов');

		return $this->render('indexdate.twig', [
			'tickets' => $tickets,
			'tickets_count' => $tickets_count,
			'tickets_sum' => $tickets_sum,
			'word' => $word,
			'biblioevents' => $biblioevents,
		]);
	}



	// Покупка билета за гостя на главном экране
	public function actionTicket($uid){

		// Проверяем наличие открытой смены у администратора
		$smena = Smena::findSmena();
		if (!$smena) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет открытой смены! Начните смену!');
			return $this->redirect(['/crm/persons/index']);
		}

		// Записываем в базу новый билет
		$ticket = new Tickets();
		$ticket->user_id = $uid;
		$ticket->smena_id = $smena->id;
		$ticket->order_id = 'administrator';
		$ticket->event_id = $_POST['event_id'];
		$ticket->date = date("Y-m-d H:i:s");
		$ticket->money = $_POST['money'];
		$ticket->count = $_POST['count'];
		$ticket->summa = $ticket->money * $ticket->count;
		$ticket->info = $_POST['info'];
		if(isset($_POST['type'])){
			if($_POST['type'] == 'Наличка'){
				$ticket->type = 1;   
			}
			else if($_POST['type'] == 'БезНал'){
				$ticket->type = 2;   
			}
			else if($_POST['type'] == 'Билетник'){
				$ticket->type = 3;   
			}
			else if($_POST['type'] == 'GoodRepublic'){
				$ticket->type = 4;   
			}
			else{
				$ticket->type = 0;   
			}
		}

		// Если билет сохранился в базу:
		// Пересчитываем кол-во принесенных денег за все его билеты
		if($ticket->save()){ 
			$sum_tickets = Tickets::find()->where(['user_id'=>$uid])->sum('summa');
			$user = Persons::findOne($uid);
			$user->sum_tickets = $sum_tickets;
			$user->save();
		}
		
		return $this->redirect(['/crm/persons/index']);
	}






	// Покупка билета внутри CRM
	public function actionTicketyandexbuy(){

		// Проверка есть ли такой гость по mail
		if(isset($_POST['id']) && !empty($_POST['id'])) {
			$pid = $_POST['id'];
			$person = Persons::findPersonById($pid);
		}
		else {
			// Создаем нового гостя
			$person = Persons::createPerson($_POST);
			$pid = $person->id; 
		}
		
		$info = $_POST['info']; // Комментарий гостя к билету


		// Какие места выбрал гость
		if(is_array($_POST['seat'])) {

			$sum = 0;
			$order_id = 't'.uniqid();

			foreach ($_POST['seat'] as $k => $v) {
				if($v > 0) {
					$ticket = new Tickets();
					$ticket->user_id = $pid;
					$ticket->order_id = $order_id;
					$ticket->event_id = $_POST['event_id'];
					$ticket->seat_id = $_POST['seat_id'][$k];
					$ticket->money = $_POST['money'][$k];
					$ticket->count = $v;
					$ticket->summa = $ticket->money * $ticket->count;
					$ticket->date = date("Y-m-d H:i:s");
					$ticket->info = $_POST['info'];
					if(isset($_POST['type'])){ // Администратор может выбрать тип
						if($_POST['type'] == 'Наличка'){
							$ticket->type = 1;   
						}
						else if($_POST['type'] == 'БезНал'){
							$ticket->type = 2;   
						}
						else{
							$ticket->type = $_POST['type'];
						}
					};
					$ticket->save();
					$sum = $sum + $ticket->summa;
				}
			};

			$tickets = Tickets::find()->where(['order_id' => $order_id])->all();
		}

		return $this->render('/pay/index.twig', [
			'user' => $person,
			'order_id' => $order_id,
			'info' => $info,
			'tickets' => $tickets,
			'sum' => $sum
		]);
	}



	public function actionCreate( $event_id=False,$seat_id=False )
	{
		$model = new Tickets();



		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			$model['event_id'] = $event_id;
			$model['seat_id'] = $seat_id;
			$model['count'] = 1;
			return $this->render('create', ['model' => $model]);
		}
	}


	/**
	 * Deletes an existing Tickets model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->del = 1;
		$model->save();
		return $this->redirect(['/crm/biblioevents']);
	}

	/**
	 * Finds the Tickets model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Tickets the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Tickets::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
