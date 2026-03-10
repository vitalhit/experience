<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Biblioevents;
use app\models\Bookingapi;
use app\models\BookList;
use app\models\Companies;
use app\models\Events;
use app\models\TasksStatus;
use app\models\Persons;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * BookingapiController implements the CRUD actions for Bookingapi model.
 */
class BookingapiController extends Controller
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
		];
	}

	/**
	 * Lists all Bookingapi models.
	 * @return mixed
	 */
	public function actionIndex($owner = false, $status = false, $list_id = false, $event_id = false, $biblioevent_id = false)
	{
		$company_id = Companies::getCompanyId();
		$b = Bookingapi::find()->where(['bookingapi.company_id' => $company_id ])->joinWith('status')->joinWith('owners.person');

		if ($owner) {
			$b->andWhere(['owner_id' => $owner]);
		}
		if ($status) {
			$b->andWhere(['status_id' => $status]);
		}
		if ($list_id) {
			$b->andWhere(['list_id' => $list_id]);
		}
		if ($event_id) {
			$b->andWhere(['event_id' => $event_id]);
		}

		if ($biblioevent_id) {
			$b->andWhere(['biblioevent_id' => $biblioevent_id]);
		}

		$bookings = $b->orderBy(['id'=>SORT_DESC])->all();

		
		$all_bookings = Bookingapi::find()->where(['company_id' => $company_id])->all();
		// Выбирает только тех администраторов, у которых есть заявки

		$owners_ids = ArrayHelper::getColumn($all_bookings, 'owner_id');
		$status_ids = ArrayHelper::getColumn($all_bookings, 'status_id');
		$event_ids = ArrayHelper::getColumn($all_bookings, 'event_id');

		$biblioevent_ids = ArrayHelper::getColumn($all_bookings, 'biblioevent_id');

		// echo "<pre>";
		// print_r($biblioevent_ids);
		// echo "</pre>";die;

		
		$owners = Users::find()->where(['status' => 10])->andWhere(['id' => $owners_ids])->all();
		$events = Events::find()->where(['id' => array_unique($event_ids)])->orderBy(['date' => SORT_DESC])->all();
		$biblioevents = Biblioevents::find()->where(['id' => array_unique($biblioevent_ids)])->all();
		$statuses = TasksStatus::find()->where(['id' => $status_ids])->all();
		$lists = BookList::find()->where(['company_id' => $company_id])->all();


		$user = Users::findOne(Yii::$app->user->id);
		$person = Persons::findOne($user->person_id??Null);
		
		return $this->render('index.twig', [
			'bookings' => $bookings,
			'owners' => $owners,
			'owner' => $owner,
			'statuses' => $statuses,
			'status' => $status,
			'lists' => $lists,
			'list_id' => $list_id,
			'events' => $events,
			'biblioevents' => $biblioevents,
			'event_id' => $event_id,
			'person' => $person
		]);
	}



	public function actionInstruction($list_id = false)
	{
		$lists = BookList::find()->where(['company_id' => Companies::getIds()])->all();
		return $this->render('instruction.twig', ['lists' => $lists, 'list_id' => $list_id ]);
	}




	public function actionView($id)
	{
		$model = Bookingapi::find()->where(['id' => $id, 'company_id' => Companies::getCompanyId()])->one();
		if (empty($model)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такой заявки!');
			return $this->redirect(['index']);
		}
		return $this->render('view', [ 'model' => $model ]);
	}




	public function actionCreate()
	{
		$model = new Bookingapi();

		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				return $this->redirect(['index']);
			} else {
				file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - чат ').json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
				return $this->redirect(['index']);
			}
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}



	public function actionUpdate($id, $go_status = false, $result = false)
	{
		$company_id = Companies::getCompanyId(); 
		$model = Bookingapi::find()->where(['id' => $id, 'company_id' => $company_id ])->one();
		if (empty($model)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такой заявки!');
			return $this->redirect(['index']);
		}{
			if( $go_status == 2 or $go_status == 3 or $go_status == 4 or $go_status == 2 or $go_status == 11){
				
				$model->status_id = $go_status;
				$user = Users::findOne(Yii::$app->user->id);
				$model->owner_id = $user->id;
				// echo "<pre>";
				// print_r($model);
				// echo "</pre>";die;
				$model->phone = preg_replace('![^0-9]+!', '', $model->phone);
				if (strpos($model->phone, '8') === 0) {
        			$model->phone = '7' . substr($model->phone, 1);
        		}

				$model->close_time = date('Y-m-d H:i:s');
				
				if( !empty($result)){$model->result = $result;}

				if ($go_status == 11){
					if ($model->save()) {
						return $this->redirect(['index?status=1']);
					}
				}
			}

		}

		$lists = BookList::find()->where(['company_id' => $company_id])->all(); 
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index'],);
		} else {
			return $this->render('update', [
				'model' => $model, 'lists' => $lists
			]);
		}
	}

	/**
	 * Deletes an existing Bookingapi model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Bookingapi model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Bookingapi the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Bookingapi::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
