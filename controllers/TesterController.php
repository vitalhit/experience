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
use app\Services\Ticket\SecretcodeService;
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
class TesterController extends Controller
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


	
	public function actionIndex($secretcode = false)
	{

		
		// $tickets = Tickets::find()->where('tickets.id = 125 ')->all();

		// foreach ($tickets as $ticket) {
		// 	$ticket->person_id = $ticket->user_id;
		// 	if (empty($ticket->order_id)){ $ticket->order_id = 'nn'.$ticket->id; }
		// 	$person = Persons::findOne($ticket->user_id);
		// 	$ticket->user = $person->user_id??Null;
		// 	if($ticket->save()){
		// 		echo "";
		// 	}else{
		// 		var_dump($ticket->getErrors());die;
		// 	}
		// }

		

			return $this->render('index.twig', ['tickets'=>$tickets]);
		
	}


		// delete
		//Tickets::find()->where(['user_id' => $pids, 'del' => 0])->joinWith(['events.biblioevents', 'events.biblioevents.cities'])->groupBy('order_id')->all();
	
		// echo "<pre>";
		// print_r($ticket);
		// echo "</pre>";die;

		// Юзер и его карточка(профиль)
		//$user = Users::findOne(Yii::$app->user->id);
		//$person = Persons::findOne($user->person_id);
		




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
