<?php

namespace app\modules\crm\controllers;

use app\models\NewsmakersEvents;
use Yii;
use app\models\Seats;
use app\models\Tickets;
use app\models\Biblioevents;
use app\models\Events;
use app\models\Companies;
use app\models\Users;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SeatsController implements the CRUD actions for Seats model.
 */
class SeatsController extends Controller
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
	 * Lists all Seats models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$comp_ids = Companies::getIds();
		$seats = Seats::find()
		->joinWith('events')
		->joinWith('events.biblioevents')
		->where(['biblioevents.company_id' => $comp_ids])
		->all();

		// echo "<pre>";
		// print_r($seats);
		// echo "</pre>";

		return $this->render('index.twig', ['seats' => $seats]);
	}



	/* Ajax билеты при выборе даты */
	public function actionGetSeats($event_id) 
	{
		$event = Events::find()->where('id = :id', [':id' => $event_id])->one();
		$s = Seats::find()->where('event_id = :eid', [':eid' => $event_id])->all();
		foreach ($s as $seat) {
			$tickets = Tickets::find()->where(['seat_id' => $seat->id, 'tickets.status' => [1,2,3,4,5,6,7]])->count();
			$seats[] = array('seat' => $seat, 'instock' => $seat->count - $tickets );
		}
		return $this->renderPartial('seats.twig', ['seats' => $seats, 'biblioevent_id' => $event->event_id]);
	}




	public function actionCreate($event_id)
	{
		$user = Users::findOne(Yii::$app->user->id);

		$event = Events::findOne($event_id);
		$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->andWhere(['company_id' => Companies::getCompanyId()])->one();
		if (empty($biblioevent)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
			return $this->redirect(['/crm/biblioevents']);
		}
		$s = Seats::find()->where(['event_id' => $event->id])->orderBy('row,sec')->all();

		foreach ($s as $seat) {
			$tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('tickets.status > 0')->sum('count');
			$seats[] = array('seat' => $seat, 'instock' => $seat->count - $tickets );
		}

		$dates = Events::getEvents($biblioevent->id);

		$model = new Seats();

		if ($model->load(Yii::$app->request->post())) {
			$model->event_id = $event->id;
			$model->save();
			if($_POST['new'] == 'Сохранить и создать еще тип билета'){
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен.');
				return $this->redirect(['/crm/seats/create?event_id='.$model->event_id.'&biblioevent_id='.$event->event_id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен. 2');
				return $this->redirect(['/crm/biblioevents/instruction?id='.$event->event_id]);
			}
		} else {
			return $this->render('create.twig', [
				'model' => $model,
				'biblioevent' => $biblioevent,
				'dates' => $dates,
				'seats' => $seats ?? null,
				'event' => $event, 
				'user' => $user
			]);
		}
	}

	public function actionPromocode($event_id)
	{
		$event = Events::findOne($event_id);
		$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->andWhere(['company_id' => Companies::getCompanyId()])->one();
		if (empty($biblioevent)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
			return $this->redirect(['/crm/biblioevents']);
		}
		$s = Seats::find()->where(['event_id' => $event->id])->orderBy('row,sec')->all();


		foreach ($s as $seat) {
			$tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('tickets.status > 0')->sum('count');
			$seats[] = array('seat' => $seat, 'instock' => $seat->count - $tickets );
		}

		// echo "<pre>";
		// print_r($s_sort);
		// echo "</pre>";

		$dates = Events::getEvents($biblioevent->id);

		$model = new Seats();

		if ($model->load(Yii::$app->request->post())) {
			$model->event_id = $event->id;
			$model->save();
			if($_POST['new'] == 'Сохранить и создать еще тип билета'){
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен.');
				return $this->redirect(['/crm/seats/create?event_id='.$model->event_id.'&biblioevent_id='.$event->event_id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен. 2');
				return $this->redirect(['/crm/biblioevents/instruction?id='.$event->event_id]);
			}
		} else {
			return $this->render('promocode.twig', [
				'model' => $model,
				'biblioevent' => $biblioevent,
				'dates' => $dates,
				'seats' => $seats,
				'event' => $event
			]);
		}
	}
	
    public function actionAdd($event_id, $name, $status) // не доделан
    {
        $model = new NewsmakersEvents();
        $model['event_id'] = $event_id;
        $model['newsmaker_id'] = $newsmaker_id ?? null;
        $model['user_id'] = $user_id ?? null;
        $model['status'] = $status;

        if ( $model->save()) {
           return $this->redirect(['promo', 'id' => $model->event_id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$user = Users::findOne(Yii::$app->user->id);

		$event = Events::find()->where('id = :eid', [':eid' => $model->event_id])->one();
		$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->andWhere(['company_id' => Companies::getCompanyId()])->one();
		if (empty($biblioevent)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такого события.');
			return $this->redirect(['/crm/biblioevents']);
		}
		$s = Seats::find()->where(['event_id' => $event->id])->orderBy('sec, row')->all();


		foreach ($s as $seat) {
			$tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('tickets.status > 0')->sum('count');
			$seats[] = array('seat' => $seat, 'instock' => $seat->count - $tickets );
		}
		
		// echo "<pre>";
		// print_r($seats);
		// echo "</pre>";

		$dates = Events::getEvents($biblioevent->id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще тип билета'){
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен.');
				return $this->redirect(['create', 'id' => $model->id, 'event_id' => $model->event_id, 'biblioevent_id' => $event->event_id]);
			} else {
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен.');
				return $this->redirect(['/crm/letters/create', 'biblioevent_id' => $event->event_id]);
			}
		} else {
			return $this->render('create.twig', [
				'model' => $model,
				'biblioevent' => $biblioevent,
				'dates' => $dates,
				'seats' => $seats,
				'user' => $user
			]);
		}
	}

	public function actionUpdateone($id, $event_id)
	{
		$model = $this->findModel($id);

		$event = Events::find()->where('id = :eid', [':eid' => $event_id])->one();
		$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->andWhere(['company_id' => Companies::getCompanyId()])->one();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if($_POST['new'] == 'Сохранить и создать еще тип билета'){
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен.');
				return $this->redirect(['create', 'id' => $model->id, 'event_id' => $model->event_id, 
					'biblioevent_id' => $event->biblioevent_id,
					'biblioevent_id' => $event->event_id
					]);
			}
			else {
				Yii::$app->getSession()->setFlash('success', 'Тип билета сохранен.');
				return $this->redirect(['/crm/letters/create', 'biblioevent_id' => $event->event_id]);
			}
		} else {
			return $this->render('create.twig', [
				'model' => $model,
				'biblioevent' => $biblioevent,
			]);
		}
	}

	/**
	 * Deletes an existing Seats model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = Seats::findOne($id);
		$event = Events::findOne($model->event_id);
		$this->findModel($id)->delete();

		return $this->redirect(['/crm/seats/create', 'event_id' => $model->event_id, 'biblioevent_id' => $event->event_id]);
	}

	/**
	 * Finds the Seats model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Seats the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Seats::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
