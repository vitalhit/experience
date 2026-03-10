<?php

namespace app\modules\crm\controllers;

use Yii;
use yii\web\Controller;
use app\models\Persons;
use app\models\Users;


/**
 * Default controller for the `crm` module
 */
class DefaultController extends Controller
{
	public $layout = 'crm';



	public function actionIndex()
	{
		return $this->render('index');
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
			$model->save();
			$user->person_id = $model->id;
			$user->save();
			Yii::$app->getSession()->setFlash('success', 'Профиль создан.');
			return $this->redirect(['/biblioevents/my']);
		} else {
			return $this->render('create.twig', ['model' => $model, 'user' => $user]);
		}
	}




	public function actionProfile()
	{
		$user = Users::findOne(Yii::$app->user->id);
		$person = Persons::findOne($user->person_id);

		if ($person) {
			return $this->render('index.twig', ['person' => $person, 'user_id' => $user->id]);
        }
        return $this->redirect(['/login']);
	}


	/**
	 * Renders the index view for the module
	 * @return string
	 */
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
