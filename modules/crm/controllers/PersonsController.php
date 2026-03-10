<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Abonements;
use app\models\Persons;
use app\models\Smena;
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
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

date_default_timezone_set('Europe/Moscow');


/**
 * PersonsController implements the CRUD actions for Persons model.
 */
class PersonsController extends Controller
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


	public $enableCsrfValidation = false;
	
	// Поиск персоны по фамилии в своей компании
	public function actionPersonsfind($name)
	{
		$name = Persons::find()
			->joinwith('visits')
			->where(['LIKE', 'second_name', $name])
			->andWhere(['persons.company_id' => Companies::getCompanyId()])	
			->andWhere(['persons.inside' => 0])	
			->all();
		return $this->renderPartial('personfind.twig', ['name' => $name]);
	}


	public function actionView($id)
	{
		$person = Persons::find()->joinWith('froms')->where(['persons.id'=>$id])->andWhere(['persons.company_id' => Yii::$app->user->identity->company_active])->one();
		if (empty($person)) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет такого гостя.');
			return $this->redirect(['/crm/persons/allpersons']);
		}
		$vis_count = count($person->visits);
		
		$abonements = Abonements::find()->where(['user_id'=>$id])->all();

		// Пересчитываем кол-во принесенных денег за всe
		$sum_visits = Visits::find()->where(['user_id'=>$id])->sum('money');
		$sum_tickets = Tickets::find()->where(['user_id'=>$id])->sum('summa');
		$sum_sells = Sells::find()->where(['user_id'=>$id])->sum('itogo');
		$sum_abonements = Abonements::find()->where(['user_id'=>$id])->sum('price');
		$sum_rents = Rents::find()->where(['person_id'=>$id])->sum('summa');

		// Записываем в профиль
		$person->sum_visits = $sum_visits;
		$person->sum_tickets = $sum_tickets;
		$person->sum_sells = $sum_sells;
		$person->sum_abonements = $sum_abonements;
		$person->sum_rents = $sum_rents;
		$person->save();

		return $this->render('view.twig', [
			'person' => $person, 
			'sum_visits' => $sum_visits,
			'abonements' => $abonements
		]);

	}

	// Страница все гости
	public function actionAllpersons()
	{
		if (empty(Companies::getCompany())) {
			Yii::$app->getSession()->setFlash('danger', 'Выберите компанию!');
			return $this->redirect(['/crm/company/my']);
		}
		// $events = Events::getEvents();
		// $e_ids = ArrayHelper::getColumn($events, 'id');
		$query = Persons::find()
		// ->joinwith('tickets')	
		// ->joinWith('abonements')
		// ->andWhere(['tickets.event_id' => $e_ids])
		->andWhere(['company_id' => Yii::$app->user->identity->company_active])
		->orderBy(['second_name' => SORT_ASC, 'name' => SORT_ASC]);
		$countQuery = clone $query;
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 50]);
		$persons = $query->offset($pages->offset)
		->limit($pages->limit)
		->all();

		// echo "<pre>";
		// print_r($persons);
		// echo "</pre>";

		return $this->render('allpersons.twig', ['persons' => $persons,'pages' => $pages]);
	}

	public function actionIndex()
	{
		if (empty(Companies::getCompany())) {
			Yii::$app->getSession()->setFlash('danger', 'Выберите компанию!');
			return $this->redirect(['/crm/company/my']);
		}

		// Получаем id текущего авторизованного пользователя
		$user_id = Yii::$app->user->id; 

		// Берем последнюю смену этого пользователя
		$last_smena = Smena::find()->where(['user_id' => $user_id])->orderBy(['id'=>SORT_DESC])->one();
		$smena = Null;
		if ($last_smena->end == NULL) {$smena = $last_smena;}
		
		//Получаем список кто пришел + кол-во визитов
		$inside = Persons::find()->joinWith('visits')->joinWith('abonements')->where(['persons.company_id' => Companies::getCompanyId()])->andWhere(['or',['inside' => 1],['inside' => 2]])->orderBy(['visits.start'=>SORT_DESC])->all();
		//Получаем список кого нет + кол-во визитов
		$today = Persons::find()->joinWith('visits')->where(['persons.company_id' => Companies::getCompanyId()])->andWhere(['inside'=> 0])->andwhere('DATE(start) = DATE(NOW())')->orderBy(['start'=>SORT_ASC])->all();


		// Определяем дату начала смен: 9 утра вчера, если сейчас меньше 9 и дата сегодня, если сейчас больше 9
		if (date("H") < 9) { // Если сейчас меньше чем 9 часов
			$thisdate = date('Y-m-d H:i:s', mktime(9, 0, 0, date('m'), date('d') - 1, date('Y'))); // Вчера 9 утра в Unix - проверить date('Y-m-d H:i:s', ...)
		} else {
			$thisdate = date('Y-m-d H:i:s', mktime(9, 0, 0, date('m'), date('d'), date('Y'))); // Сегодня 9 утра в Unix - проверить date('Y-m-d H:i:s', ...)
		}
		
		// ДЛЯ СВЕРКИ !!! Считаем все деньги с 9 утра до текущего момента, игнорируя смены.
		// Чтобы знать всю сумму, даже не попавшую в смены.
		$summnal1 = Visits::find()->where(['company_id' => Companies::getCompanyId()])->andWhere(['type' => 1])->andwhere('end > :a', ['a' => $thisdate])->sum('money');
		$summbez1 = Visits::find()->where(['company_id' => Companies::getCompanyId()])->andWhere(['type' => 2])->andwhere('end > :a', ['a' => $thisdate])->sum('money');
		$summgr1 = Visits::find()->where(['company_id' => Companies::getCompanyId()])->andWhere(['type' => 4])->andwhere('end > :a', ['a' => $thisdate])->sum('money');
		
		// echo "<pre>";
		// print_r($thisdate);
		// echo "</pre>";

		$summnal4 = Abonements::find()->where(['type' => 1])->andwhere('create_at > :a', ['a' => $thisdate])->sum('price');
		$summbez4 = Abonements::find()->where(['type' => 2])->andwhere('create_at > :a', ['a' => $thisdate])->sum('price');
		$summgr4 = Abonements::find()->where(['type' => 4])->andwhere('create_at > :a', ['a' => $thisdate])->sum('price');

		$summnal = $summnal1 + $summnal4;
		$summbez = $summbez1 + $summbez4;
		$summgr = $summgr1 + $summgr4;

		return $this->render('index.twig', [
			'smena' => $smena,
			'inside' => $inside,
			'today' => $today,
			'summnal' => $summnal,
			'summbez' => $summbez,
			'summpad' => $summpad ?? null,
			'summgr' => $summgr
			]);
	}

	// Поиск гостя по mail и телефону
	public function actionPersonisset()
	{
		$person = Persons::find()
		->filterWhere(['mail' => $_GET['mail'], 'phone' => $_GET['phone']])
		->andWhere(['company_id' => Yii::$app->user->identity->company_active])->one();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $person;
	}

	// Добавить гостя
	public function actionCreate()
	{
		$model = new Persons();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$user = Users::find()->where(['email' => $model->mail])->one();
			// Если есть - пропишем персоне USER_ID, если пуст - создадим юзера
			if (!empty($user)) {
				$model->user_id = $user->id;
				$model->save();
			} else {
				// если есть поле mail
				if (!empty($model->mail)) {
					$user = Users::createUser($model);
				}
			}
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			// foreach ($model->getErrors() as $key => $value) {
			// 	echo $key.': '.$value[0];
			// }
			// return;
			return $this->render('create', ['model' => $model]);
		}
	}

	public function actionCreatebegin()
	{
		$model = new Persons();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['viewbegin', 'id' => $model->id]);
		} else {
			return $this->render('create', ['model' => $model]);
		}
	}

	// Пересчитываем суммы денег у всех гостей и выводим на странице статусы
	public function actionRecount(){

		$users = Persons::find()->all();

		foreach ($users as $user) {

		// Пересчитываем кол-во принесенных денег за всe
			$sum_visits = Visits::find()->where(['user_id'=>$user->id])->sum('money');
			$sum_tickets = Tickets::find()->where(['user_id'=>$user->id])->sum('summa');
			$sum_sells = Sells::find()->where(['user_id'=>$user->id])->sum('itogo');
			$sum_abonements = Abonements::find()->where(['user_id'=>$user->id])->sum('price');
			$sum_rents = Rents::find()->where(['person_id'=>$user->id])->sum('summa');

		// Записываем в профиль
			$user = Persons::findOne($user->id);
			$user->sum_visits = $sum_visits;
			$user->sum_tickets = $sum_tickets;
			$user->sum_sells = $sum_sells;
			$user->sum_abonements = $sum_abonements;
			$user->sum_rents = $sum_rents;
			$user->save();
		}

		return $this->redirect(['/crm/persons/status']);
	}






	// Покупка билета за гостя (отрисовка формы покупки)
	public function actionFormticket($uid){
		$user = Persons::findOne($uid);
		$events = Events::find()->orderBy(['date'=>SORT_ASC])->andwhere('DATE(date) >= DATE(NOW())')->all();
		$end = date("Y-m-d H:i:s");

		return $this->renderPartial('form_ticket.twig', ['user'=>$user, 'end'=>$end, 'events'=>$events]);
	}


	public function actionFormsell($uid){

		$goods = Goods::find()->all();
		$user = Persons::findOne($uid);

		return $this->renderPartial('form_sell.twig', ['goods'=>$goods, 'user'=>$user]);
	}


	// Покупка гостем какого-нибудь товара
	public function actionSell($uid){
		
		// Проверяем наличие открытой смены у администратора
		$smena = Smena::findSmena();
		if (!$smena) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет открытой смены! Начните смену!');
			return $this->redirect(['/crm/persons']);
		}

		$sells = new Sells();
		$sells->smena_id = $smena->id;
		$sells->user_id = $uid;
		$sells->good_id = $_POST['good_id'];
		$sells->info = $_POST['info'];
		$sells->price = $_POST['price'];
		$sells->count = $_POST['count'];
		$sells->itogo = $_POST['itogo'];
		if(isset($_POST['type'])){
			if($_POST['type'] == 'Наличка'){
				$sells->type = 1;	
			}
			else if($_POST['type'] == 'БезНал'){
				$sells->type = 2;	
			}
			else if($_POST['type'] == 'GoodRepublic'){
				$sells->type = 4;	
			}
			else if($_POST['type'] == 'Не оплачено'){
				$sells->type = 0;	
			}
			else{
				$sells->type = 3;	
			}
		}

		// Если покупка сохранилась в базу:
		// Пересчитываем кол-во принесенных денег за все его покупки
		if($sells->save()){ 
			$sum_sells = Sells::find()->where(['user_id'=>$uid])->sum('itogo');
			$user = Persons::findOne($uid);
			$user->sum_sells = $sum_sells;
			$user->save();
		}

		return $this->redirect(['/crm/persons']);
	}





	public function actionVisit($uid)
	{

		// Получаем id текущего авторизованного пользователя
		$admin = Users::findOne(Yii::$app->user->id);

		// Проверяем наличие открытой смены у администратора
		$smena = Smena::findSmena();
		if (!$smena) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет открытой смены! Начните смену!');
			return $this->redirect(['/crm/persons']);
		}

		$user = Persons::findOne($uid);

		$visit = new Visits();
		$visit->user_id = $user->id;
		$visit->smena_id = $smena->id;
		$visit->company_id = $admin->company_active;
		$visit->start = date("Y-m-d H:i:s");
		if ($visit->save()) {		
			$user->inside = 1;
			$user->save();
		};

        Yii::$app->getSession()->setFlash('success', 'Визит начат!');
		return $this->redirect(['/crm/persons']);
	}





	public function actionVisitabonement($uid){
		
		// Проверяем наличие открытой смены у администратора
		$smena = Smena::findSmena();
		if (!$smena) {
			Yii::$app->getSession()->setFlash('danger', 'У вас нет открытой смены! Начните смену!');
			return $this->redirect(['/crm/persons']);
		}

		$user = Persons::findOne($uid);
		$user->inside = 2;
		$user->save();

		$visit = new Visits();
		$visit->user_id = $uid;
		$visit->type = 5;
		$visit->smena_id = $smena->id;
		$visit->start = date("Y-m-d H:i:s");
		$visit->save();

		return $this->redirect(['/crm/persons']);
	}


	public function actionEndabonement($uid){

		$visit = Visits::find()->where(['user_id'=>$uid])->orderBy(['id'=>SORT_DESC])->one();
		$visit->end = date("Y-m-d H:i:s");

		$visitlast = Visits::find()->where(['user_id'=>$uid])->andwhere(['type'=> 5])->andwhere('DATE(end) = DATE(NOW())')->orderBy(['id'=>SORT_DESC])->one();


		if($visit->save()){
			$user = Persons::findOne($uid);
			$user->inside = 0;
			$user->lastvisit = $visit->end;
			$user->save();

			$abonement = Abonements::find()->where(['user_id'=>$uid])->andwhere(['status'=>1])->one();
			if($abonement->balance == 1){
				$abonement->status = 0;
			}
			if($visitlast){
			}		
			else{
				$abonement->balance = $abonement->balance - 1;	
			}
		}
		$abonement->save();




		return $this->redirect(['/crm/persons']);
	}



	// Форма завершения визита
	public function actionFormendvisit($uid){

		$user = Persons::findOne($uid);
		$visit = Visits::find()->where(['user_id'=>$uid])->orderBy(['id'=>SORT_DESC])->one();
		$end = date("Y-m-d H:i:s");
		$timein = intval((strtotime($end) - strtotime($visit->start))/60); // минут в гостях

		return $this->renderPartial('form_endvisit.twig', ['user' => $user, 'visit' => $visit, 'end'=> $end, 'timein' => $timein]);
	}



	// Завершение визита по тарифу
	public function actionVisitend($uid){

		// Получаем id текущего администратора
		$admin_uid = Yii::$app->user->id;

		// Берем последнюю смену этого администратора
		$last_smena = Smena::find()->where(['user_id' => $admin_uid])->orderBy(['id'=>SORT_DESC])->one();
		$smena_id = 0;

		// Проверяем что смена открыта
		if ( is_null($last_smena->end) ) {
			$smena_id = $last_smena->id;
		}

		// Обновляем запись визита
		$visit = Visits::find()->where(['user_id'=>$uid])->orderBy(['id'=>SORT_DESC])->one();
		$visit->smena_id = $smena_id;
		$visit->end = $_POST['end'];
		$visit->discount_money = (int) $_POST['discount_money'];
		$visit->money = $_POST['fin_money'];

		if(isset($_POST['type'])){
			if($_POST['type'] == 'Наличка'){
				$visit->type = 1;	
			}
			else if($_POST['type'] == 'БезНал'){
				$visit->type = 2;	
			}
			else if($_POST['type'] == 'GoodRepublic'){
				$visit->type = 4;	
			}
			else if($_POST['type'] == 'Билетник'){
				$visit->type = 3;	
			}
			else if($_POST['type'] == 'Абонемент'){
				$visit->type = 5;	
			}
			else{
				$visit->type = 6;	
			}
		}

		// Если визит сохранился в базу:
		// Присваиваем юзеру 0 = он не в гостях
		// Записываем в его профиль дату последнего визита
		// Пересчитываем кол-во принесенных денег за все его визиты
		if($visit->save()){ 
			$sum_visits = Visits::find()->where(['user_id'=>$uid])->sum('money');
			$person = Persons::findOne($uid);
			$person->inside = 0;
			$person->lastvisit = $visit->end;
			$person->sum_visits = $sum_visits;
			$person->save();
		}

		Sells::updateAll(['type' => $visit->type, 'user_id' => $uid], 'type = 0');

		return $this->redirect(['/crm/persons']);
	}







	public function actionViewbegin($id){


		$user = Persons::find()->joinWith('visits')->where(['persons.id'=>$id])->one();
		$vis_count = count($user->visits);
		$visitword = VisitsController::pluralForm($vis_count, 'визит', 'визита', 'визитов');
		$summ = Persons::find()->joinWith('visits')->where(['persons.id'=>$id])->sum('money');

		$this->actionVisit($id);

		return $this->render('view.twig', [
			'user' => $user, 'visitword' => $visitword, 'summ' => $summ
			]);

	}



	/**
	* Creates a new Persons model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	* @return mixed
	*/
	public function actionAdd()
	{
		$addperson = new Persons();
		$addperson->name = $_POST['name'];
		$addperson->second_name = $_POST['second_name'];
		$addperson->middle_name = $_POST['middle_name'];
		$addperson->mail = $_POST['mail'];
		$addperson->phone = $_POST['phone'];
		$addperson->birthday = $_POST['birthday'];
		$addperson->status = $_POST['status'];
		$addperson->groups = $_POST['group'];
		$addperson->sex = $_POST['sex'];
		$addperson->discount = $_POST['discount'];
		$addperson->froms_id = $_POST['froms'];
		$addperson->sendmail = $_POST['sendmail'];
		$addperson->info = $_POST['info'];
		$addperson->save();

		return $this->render('create.twig', [
			'addperson' => $addperson,
			]);
	}



	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
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
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', ['model' => $model]);
		}
	}

	/**
	* Deletes an existing Persons model.
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
	* Finds the Persons model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param integer $id
	* @return Persons the loaded model
	* @throws NotFoundHttpException if the model cannot be found
	*/
	protected function findModel($id)
	{
		if (($model = Persons::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
