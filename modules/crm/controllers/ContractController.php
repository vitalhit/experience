<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\AuthAssignment;
use app\models\Biblioevents;
use app\models\Companies;
use app\models\CompanyPayments;
use app\models\CompanyUser;
use app\models\Contract;
use app\models\Docs;
use app\models\EventFinance;
use app\models\Events;
use app\models\LogPay;
use app\models\Messages;
use app\models\Persons;
use app\models\Projects;
use app\models\Rents;
use app\models\Tasks;
use app\models\Tickets;
use app\models\Visits;
use app\models\UploadForm;
use app\models\Users;
use app\models\Vk;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use \VK\Client\VKApiClient;

/**
 * ContractController implements the CRUD actions for Companies model.
 */
class ContractController extends Controller
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

	public $enableCsrfValidation = false;

	
	


	public function actionAbout($id = false)
	{	
		// Если нет персоны - редирект на создание
		if (empty(Persons::isPerson())) {
			Yii::$app->getSession()->setFlash('danger', 'Заполните профиль!');
			return $this->redirect(['/profile/create']);
		}
		
		if (!empty($id)) { $company = Companies::setCompany($id); }

		if (empty(Companies::getCompany())) {
			Yii::$app->getSession()->setFlash('danger', 'Выберите компанию!');
			return $this->redirect(['/crm/company/my']);
		}

		$user = Users::findOne(Yii::$app->user->id);
		$company = Companies::findOne($user->company_active);
		$biblio = Biblioevents::find()->where(['company_id' => $company->id])->all();
		$biblio_ids = ArrayHelper::getColumn($biblio, 'id');
		$biblioevents = count($biblio);
		
		$eve = Events::find()->where(['event_id' => $biblio_ids])->all();
		$eve_ids = ArrayHelper::getColumn($eve, 'id');
		$events = count($eve_ids);

		$tickets = Tickets::find()->where(['event_id' => $eve_ids])->count();
		$persons = Tickets::find()->where(['event_id' => $eve_ids])->groupBy('user_id')->count();

		$pro = Projects::find()->where(['company_id' => $company->id])->all();
		$pro_ids = ArrayHelper::getColumn($pro, 'id');
		$projects = count($pro_ids);

		$tasks = Tasks::find()->where(['project_id' => $pro_ids])->count();
		$tasks_new = Tasks::find()->where(['project_id' => $pro_ids])->andWhere(['tasks.status_id' => 1])->andWhere(['tasks.owner_id' => $user->id])->count();
		$tasks_check = Tasks::find()->where(['project_id' => $pro_ids])->andWhere(['tasks.status_id' => 5])->andWhere(['tasks.creator_id' => $user->id])->count();

		$z_tickets = Tickets::find()->where(['event_id' => $eve_ids, 'status' => [1,2,3,4], 'del' => 0 ])->sum('summa');
		$z_rents = Rents::find()->where(['company_id' => $company->id])->andWhere(['status' => [1,2,3,4]])->sum('summa');
		
		// $z_tickets1 = Tickets::find()->where(['event_id' => $eve_ids, 'status' => [1,2,3,4,6,7], 'del' => 0 ])->all();

		// echo "<pre>";
		// print_r($z_tickets1);
		// echo "</pre>";

		// $z_visits = Visits::find()->where(['company_id' => $company->id])->sum('money'); ???
		// $z_finance = Visits::find()->where(['company_id' => $company->id])->sum('money'); - будут расходы в ожидании, еще не отправленные

		$m_tickets = Tickets::find()->where(['event_id' => $eve_ids])->andWhere(['status' => 5, 'tickets.del' => 0])->sum('summa');
		$m_rents = Rents::find()->where(['company_id' => $company->id])->andWhere(['status' => [1,2,3,4]])->sum('summa');
		$m_visits = Visits::find()->where(['company_id' => $company->id])->sum('money');
		$m_finance = EventFinance::find()->where(['company_id' => $company->id])->andWhere(['status' => 3])->sum('summa');

		if (AuthAssignment::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['item_name' => 'admin'])->one()) {
			$thisdate = date('Y-m-d');
			$cron['mes'] = Messages::find()->where(['DATE(create_at)' => $thisdate])->andWhere(['type' => 2])->count();
			$cron['mes_need'] = Tickets::find()->where('tickets.send = 0')->andWhere(['or', ['tickets.status' => 5], ['and', 'tickets.status = 1', 'tickets.summa = 0']])->count();
			$cron['lk'] = LogPay::find()->where(['DATE(create_at)' => $thisdate])->andWhere(['status' => 3])->sum('order_id');
			$cron['lk_need'] = Persons::find()->where('user_id is Null')->andWhere('status is Null')->andWhere(['like', 'mail', '@'])->andWhere('company_id > 0')->andWhere('id > 9000')->count();

			$cron['rem'] = Messages::find()->where(['DATE(create_at)' => $thisdate])->andWhere(['type' => 1])->count();
			$cron['log'] = LogPay::find()->where(['DATE(create_at)' => $thisdate])->andWhere(['status' => 2])->count();
		} else {
			$cron = null;
		}

		$vk = Vk::Noti(Yii::$app->user->id);

		return $this->render('about.twig', [
			'user_id' => $user->id,
			'company' => $company,
			'biblioevents' => $biblioevents,
			'events' => $events,
			'tickets' => $tickets,
			'persons' => $persons,
			'projects' => $projects,
			'tasks' => $tasks,
			'tasks_new' => $tasks_new,
			'tasks_check' => $tasks_check,
			'm_tickets' => $m_tickets,
			'z_tickets' => $z_tickets,
			'm_rents' => $m_rents,
			'z_rents' => $z_rents,
			'm_visits' => $m_visits,
			'm_finance' => $m_finance,
			'cron' => $cron,
			'vk' => $vk
		]);
	}






	public function actionVkupdate()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$model = Yii::$app->request->post();
		$person = Persons::findOne(['user_id' => Yii::$app->user->id]);

		$vk = new Vk();
		$vk->company_id = Companies::getCompanyId();
		$vk->vk_id = $_POST['vk_id'];
		$vk->group_id = $_POST['group_id'];
		$vk->user_id = Yii::$app->user->id;
		$vk->name = 'Уведомления';
		if (!empty($_POST['type'])) {$vk->type = $_POST['type'];}
		if ($vk->save()) {
			if ($_POST['vk_id'] > 0) {
				Vk::Send($person->name.', добро пожаловать в iGoEvent.com! Я буду сообщать о важных событиях.', [$_POST['vk_id']]);
				return ['status' => 'js_alert_success', 'msg' => 'Благодарим!'];
			} else {
				return ['status' => 'js_alert_success', 'msg' => 'Вы отписаны :('];
			}
		}
	}




	public function actionTeam()
	{
		$company = Companies::getCompany();
		// join персону не из user, а персону к которой есть доступ у этой компании из табицы персон, где company_id и user_id
		$users = CompanyUser::find()->where(['company_user.company_id' => $company->id])->joinWith('user')->joinWith('person')->joinWith('role')->asArray()->all();

		// echo "<pre>";
		// print_r($users);
		// echo "</pre>";

		return $this->render('team.twig', ['company' => $company, 'users' => $users]);
	}



	public function actionDohod()
	{
		$company = Companies::getCompany();
		$contract = Contract::findOne(['global_id' => $company->global_id]);
		$today = date('Y-m-d');
		$start = date('Y-m-d', strtotime($contract->date));

		$biblioevents = Biblioevents::find()->where(['company_id' => $company->id])->all();

		$next = date('Y-m-d', mktime(0, 0, 1, date('m',strtotime($contract->date)), 01, date('Y',strtotime($contract->date))));
		
		while (date('Y-m-d', strtotime("$next")) < $today){
			$month = date('m', strtotime($next));
			$year = date('Y', strtotime($next));
			$dohod = null;
			foreach ($biblioevents as $biblioevent) {
				$d = Tickets::Dohod($company, $biblioevent, $month, $year);
				if (!empty($d)) {
					$dohod[] = $d;
				}
			}
			if (!empty($dohod)) {
				$itog[] = array('date' => $next, 'dohod' => $dohod);
			}
			$next = date('Y-m-d', strtotime("$next +1 month"));
		}
		$payments = CompanyPayments::find()->where(['company_id' => $company->id])->all();
		$akt = Companies::CompAkt($company->id, $start, date('Y-m-d H:i:s'));

		// echo "<pre>";
		// print_r($akt);
		// echo "</pre>";

		return $this->render('dohod.twig', ['itog' => $itog, 'payments' => $payments, 'akt' => $akt]);
	}




	public function actionVuvod()
	{
		$payment = new CompanyPayments();
		$payment->company_id = Companies::getCompanyId();
		$payment->user_id = Yii::$app->user->id;
		$payment->summa = $_POST['summa'];
		$payment->info = $_POST['info'];
		$payment->status = 1;
		if ($payment->save()) {
			Yii::$app->getSession()->setFlash('success', 'Выплата заказана');
			return $this->redirect(['/crm/company/dohod']);
		};
	}


	public function actionRashod()
	{
		return $this->render('rashod.twig');
	}


	public function actionVozvrat()
	{
		$biblio = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->all();
		$biblio_ids = ArrayHelper::getColumn($biblio, 'id');
		$eve = Events::find()->where(['event_id' => $biblio_ids])->all();
		$eve_ids = ArrayHelper::getColumn($eve, 'id');

		$tickets = Tickets::find()->where(['event_id' => $eve_ids, 'status' => 7 ])->all();
		// echo "<pre>";
		// print_r($tickets);               
		// echo "</pre>";

		return $this->render('vozvrat.twig', ['tickets' => $tickets]);
	}


	public function actionTarif()
	{
		return $this->render('tarif.twig');
	}

	public function actionDogovor()
	{
		$company = Companies::getCompany();
		$users = CompanyUser::find()->where(['company_user.company_id' => $company->id])->joinWith('person')->joinWith('role')->asArray()->all();
		if (!empty($company)) {
			return $this->render('dogovor.twig', ['company' => $company, 'users' => $users]);
		} else {
			Yii::$app->getSession()->setFlash('danger', 'Нет доступа к этой компании.');
			return $this->redirect(['/crm/company']);
		}
	}



	// удаляем фото по ajax
	public function actionDelimage($img_id)
	{
		$img = Docs::find()->where(['id' => $img_id, 'company_id' => Companies::getCompanyId()])->one();
		$img->status = 0;
		if ($img->save()) {
			return $img->id;
		}
	}


	public function actionAkts()
	{
		$today = date('Y-m-d H:i:s');
		$company = Companies::getCompany();
		// Дата регистрации договора
		$start = date('Y-m-d H:i:s', strtotime($company->date));
		// Начало следующего месяца
		$next = date('Y-m-d H:i:s', mktime(0, 0, 1, date('m',strtotime($company->date)), 01, date('Y',strtotime($company->date))));
		
		while (date('Y-m-d H:i:s', strtotime("$next +1 month")) < $today){
			$date = date('Y-m-d', strtotime("$next +1 month"));
			$next = date('Y-m-d H:i:s', strtotime("$next +1 month"));
			$docs = Docs::find()->where(['date' => $date, 'company_id' => $company->id, 'status' => [1, 2, 3]])->all();

			$wait = Docs::find()->where(['docs.company_id' => $company->id, 'docs.date' => $date, 'docs.status' => 1])->all();
			if (!empty($wait)) {
				$status = 'wait';
			} else {
				$back = Docs::find()->where(['docs.company_id' => $company->id, 'docs.date' => $date, 'docs.status' => 3])->joinWith('verify')->all();
				if (!empty($back)) {
					$status = 'back';
				} else {
					$done = Docs::find()->where(['docs.company_id' => $company->id, 'docs.date' => $date, 'docs.status' => 2])->joinWith('verify')->all();
					if (!empty($done)) {
						$status = 'done';
					}				
				}
			}
			$month[] = array('month' => $next, 'docs' => $docs, 'status' => $status);
		}

		// echo "<pre>";
		// print_r($month);               
		// echo "</pre>";

		return $this->render('akts.twig', ['company' => $company, 'month' => $month]);
	}



	public function actionAkt($date)
	{
		$company = Companies::find()->where(['id' => Companies::getCompanyId()])->one();
		$end = date('Y-m-d H:i:s', strtotime("$date +1 month -1 day"));
		$start = $date;
		$date = date('Y-m-d H:i:s', strtotime("$date +1 month"));

		// Остаток на начало (нужна история выплат)
		$ostatok = 0;
		$ostatok_p = Companies::Propis($ostatok);

		// Сумма билетов
		$biblio = Biblioevents::find()->where(['company_id' => $company->id])->all();
		$biblio_ids = ArrayHelper::getColumn($biblio, 'id');
		$eve = Events::find()->where(['event_id' => $biblio_ids])->all();
		$eve_ids = ArrayHelper::getColumn($eve, 'id');
		$tik_sum = Tickets::find()->where(['event_id' => $eve_ids, 'status' => 5])->andWhere(['between', 'date', $start, $date ])->sum('summa');
		$tik_sum_p = Companies::Propis($tik_sum);
		$tik_sum_yr_p = Companies::Propis(0);

		// Cумма вознаграждения Агента 
		$vozn = $tik_sum * 0.06;
		$vozn_p = Companies::Propis($vozn);

		//  Cумма возвратов
		$tik_back = Tickets::find()->where(['event_id' => $eve_ids, 'status' => 7])->andWhere(['between', 'date', $start, $date ])->sum('summa');
		$tik_back_p = Companies::Propis($tik_back);

		// Вознаграждение за возвраты (% агенту)
		$vozn_back = $tik_back * 0;
		$vozn_back_p = Companies::Propis($vozn_back);

		// Денежные средства, удержанные Агентом для взаимозачета
		$block = 0;
		$block_p = Companies::Propis($block);

		// Сумма перечисления 
		$pere = $ostatok + $tik_sum - $vozn - $vozn_back - $block;
		$pere_p = Companies::Propis($pere);

		// Остаток у Агента
		$ostatok_end = ($ostatok + $tik_sum - $vozn - $vozn_back - $block) - $pere;
		$ostatok_end_p = Companies::Propis($ostatok_end);

		$money = array(
			'ostatok' => $ostatok,
			'ostatok_p' => $ostatok_p,
			'tik_sum' => $tik_sum,
			'tik_sum_p' => $tik_sum_p,
			'tik_sum_yr_p' => $tik_sum_yr_p,
			'vozn' => $vozn,
			'vozn_p' => $vozn_p,
			'tik_back' => $tik_back,
			'tik_back_p' => $tik_back_p,
			'vozn_back' => $vozn_back,
			'vozn_back_p' => $vozn_back_p,
			'block' => $block,
			'block_p' => $block_p,
			'pere' => $pere,
			'pere_p' => $pere_p,
			'ostatok_end' => $ostatok_end,
			'ostatok_end_p' => $ostatok_end_p
		);

		// echo "<pre>";
		// print_r($money);              
		// echo "</pre>";

		$content = $this->renderPartial('akt.twig', ['company' => $company, 'start' => $start, 'end' => $end, 'money' => $money]);

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
			'options' => ['title' => 'Акт'],

		]);
		$pdf->getApi()->addPage();

    	// return the pdf output as per the destination setting
		return $pdf->render(); 
	}



	// Загрузка сканов
	public function actionUpload( $date = false, $task_id = false )
	{
		// file_put_contents('test.txt', PHP_EOL . Date('d.m.Y H:i:s - пост').json_encode($_POST,JSON_UNESCAPED_UNICODE), FILE_APPEND);        
		$model = new UploadForm();

		if (Yii::$app->request->isPost) {
			$model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
			file_put_contents('test.txt', PHP_EOL . Date('d.m.Y H:i:s - картинка ').json_encode($model->imageFiles), FILE_APPEND);        

			$model->date = $_POST['UploadForm']['date'];
			$model->task_id = $_POST['UploadForm']['task_id'];
			$model->info = $_POST['UploadForm']['info'];
			if ($model->upload()) {
				if (!empty($task_id)) {
					return $this->redirect(['/crm/tasks/update?id='.$task_id]);
				} else {
					return $this->redirect(['/crm/company/akts']);
				}
			}
		}

		return $this->render('upload', ['model' => $model, 'date' => $date, 'task_id' => $task_id]);
	}



	public function actionAdd()
	{
		$company = Companies::findOne($_POST['company_id']);
		$users = CompanyUser::find()->where(['company_user.company_id' => $company->id])->joinWith('person')->joinWith('role')->asArray()->all();

		$user = new CompanyUser;
		$user->company_id = $_POST['company_id'];
		$user->user_id = $_POST['user_id'];
		$user->bywho = Yii::$app->user->id;
		$user->save();

		$this_user = Users::findOne($_POST['user_id']);
		$pers = Persons::findOne($this_user->person_id);

		$persona = new Persons;
		$persona->company_id = $_POST['company_id'];
		$persona->user_id = $_POST['user_id'];
		$persona->name = $pers->name;
		$persona->second_name = $pers->second_name;
		$persona->middle_name = $pers->middle_name;
		$persona->mail = $pers->mail;
		$persona->phone = $pers->phone;
		$persona->sex = $pers->sex;
		$persona->birthday = $pers->birthday;
		$persona->city = $pers->city;
		$persona->image = $pers->image;
		$persona->save();

		return $this->redirect(['/crm/company/team?id='.$_POST['company_id']]);
	}



	public function actionUnlink($id, $company_id)
	{
		$company = Companies::findOne($company_id);
		$company_user = CompanyUser::find()->where(['company_user.company_id' => $company_id])->andWhere(['user_id' => $id])->one();
		$company_user->delete();

		$user = Users::findOne($id);
		if (!empty($user)) {
			$user->company_active = 0;
			$user->save();
		}

		return $this->redirect(['/crm/company/team?id='.$company->id]);
	}


	public function actionUpdate()
	{
		$model = Companies::getCompany();
		// echo "<pre>";
		// print_r($model);               
		// echo "</pre>";
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['/crm/company']);
		} else {
			return $this->render('update.twig', ['model' => $model, 'company' => $model]);
		}
	}


	public function actionWhat()
	{
		return $this->render('what.twig');
	}


	protected function findModel($id)
	{
		if (($model = Companies::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
