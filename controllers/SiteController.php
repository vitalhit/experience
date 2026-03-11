<?php

namespace app\controllers;

 
use app\Services\Eventcollection\Api;

use Yii;
use app\models\Ads;
use app\models\Band;
use app\models\BandEvent;
use app\models\Biblioevents;
use app\models\BiblioeventSection;
use app\models\BiblioeventBand;
use app\models\Bookingapi;
use app\models\Brands;
use app\models\Cities;
use app\models\Companies;
use app\models\Categoryevents;
use app\models\Contragent;
use app\models\Events;
use app\models\EventFinance;
use app\models\Img;
use app\models\Items;
use app\models\Feedback;
use app\models\Festival;
use app\models\Landing;
use app\models\LoginForm as Login;
use app\models\LogPage;
use app\models\LogCron;
use app\models\Messages;
use app\models\Newsmakers;
use app\models\NewsmakersEvents;
use app\models\Page;
use app\models\PasswordResetRequest;
use app\models\Persons;
use app\models\Places;
use app\models\Posts;
use app\models\Rents;
use app\models\ResetPassword;
use app\models\Rooms;
use app\models\Seats;
use app\models\Seatings;
use app\models\Section;
use app\models\Signup;
use app\models\Smena;
use app\models\Tickets;
use app\models\User;
use app\models\AuthHandler;
use Da\QrCode\QrCode;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\ArrayHelper;


//use yii\data\ActiveDataProvider;
//use yii\web\Controller;
//use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;



class SiteController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	public $enableCsrfValidation = false;
	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
			'auth' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'onAuthSuccess'],
			],
		];
	}

	public function onAuthSuccess($client)
	{
		(new AuthHandler($client))->handle();
	}


	public function actionRobots()
	{
		return "User-agent: *<br>
		Disallow: /";
	}


	public function actionTrim()
	{
		
		$persons = Persons::find()->all();
		foreach ($persons as $person) {
			if (!empty($person->mail)){
				$person->mail  = trim($person->mail);
				$person->save();
				echo $person->email."<br>";
			}
		}
		$users = User::find()->all();	
		foreach ($users as $user) {
			if (!empty($user->email)){
				$user->email  = trim($user->email);
				$user->save();
				echo $user->email."<br>";
			}
		}
	}

	public function actionIndex()
	{
		$host = parse_url(Yii::$app->request->getHostInfo(), PHP_URL_HOST) ?? '';
		$isExperience = ($host === 'experience.igoevent.com' || strpos($host, 'experience.') === 0);
		if ($isExperience) {
			if (Yii::$app->user->isGuest) {
				return $this->redirect(['/login']);
			}
			return $this->redirect(['/experience/order']);
		}

		$this->layout='front';

		$cities = Cities::Active();
		$model = new Signup();
		$login = new Login();
		if ($model->load(Yii::$app->getRequest()->post())) {
			if ($user = $model->signup()) {
				LogPage::setLog('site/index', 1);

				// Проверяем что есть профиль
				if (isset($user->person_id)) {		
					return $this->redirect('/crm/company');
				} else {
					return $this->redirect('/crm/company');
				}
			} else {
				LogPage::setLog('site/index', 2, 'Регистрация не удалась: '.json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
			}
		}
		return $this->render('index', ['model' => $model, 'login' => $login, 'cities' => $cities]);
	}

	public function actionLogin()
	{
		$this->layout='front-login';

		if (!Yii::$app->getUser()->isGuest) {
			return $this->goHome();
		}

		$model = new Login();
		if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
			return $this->redirect('/experience/order');
		} else {
			return $this->render('login', ['model' => $model]);
		}
	}

	public function actionSignup()
	{
		$this->layout='front';

		$model = new Signup();
		if ($model->load(Yii::$app->getRequest()->post())) {
			if ($user = $model->signup()) {
				LogPage::setLog('site/index', 1, 'Регистрация');
				return $this->redirect('/crm/company');
			}else {
				LogPage::setLog('site/index', 2, 'Регистрация не удалась: '.json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
			}
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}



	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}



	public function actionRequestPasswordReset()
	{	
		$this->layout='service';
		$model = new PasswordResetRequest();
		if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->getSession()->setFlash('success', 'Проверьте вашу почту, мы отправили вам ссылку для востановления пароля.');

				return $this->redirect(['index']);
			} else {
				Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
			}
		}

		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}



	public function actionResetPassword($token)
	{	
		$this->layout='service';
		try {
			$model = new ResetPassword($token);
			$identity = User::findOne(['password_reset_token' => $token]);
		} catch (InvalidParamException $e) {
            // throw new BadRequestHttpException($e->getMessage());
			throw new BadRequestHttpException('Cсылка устарела —  вы не можете воспользоваться ей.');
		}

		if ($model->load(Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->getSession()->setFlash('success', 'Новый пароль сохранен.');
			// логиним пользователя
			LogPage::setLog('site/reset-password', 1, 'Новый пароль');
			Yii::$app->user->login($identity);
			return $this->redirect('/profile/index');
    		//return $this->goHome();
		} else {
			LogPage::setLog('site/reset-password', 2, 'Пароль не удался: '.json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}



	// События в городе
	public function actionCity($city, $date = false)
	{
		$this->layout='page';
		$city = Cities::find()->where('cities.alias = :a', [':a' => Yii::$app->request->get('city')])->one();
		$cities = Cities::Active();
		if (empty($city)) { return $this->render('404.twig'); }

		$mon = date("d.m.Y", strtotime("last Monday")); // Начало этой недели

		// Все события у которых есть активная дата и она не прошла
		$biblioevents = Biblioevents::find()->where(['biblioevents.city' => $city->id, 'biblioevents.status' => 1])->joinWith('oneactiveevent')->all();
		$bib_ids = array_unique(ArrayHelper::getColumn($biblioevents, 'id'));

		// Если дата PAST - все события БЕЗ активных дат
		if ($date == 'past') {
			$biblioevents = Biblioevents::find()->where(['city' => $city->id, 'status' => 1])->andWhere(['not in', 'id',  $bib_ids])->all();
		} elseif (!empty($date)) {
			$biblioevents = Biblioevents::find()->where(['biblioevents.city' => $city->id, 'biblioevents.status' => 1])
			->joinWith('events')->andWhere(['DATE(events.date)' => $date, 'events.status' => 1])->all();
		// echo "<pre>";
		// print_r($ads);
		// echo "</pre>";
		}

		$ads = Ads::City($city->id);

		$iuser = Yii::$app->user->identity;

		return $this->render('city.twig', ['biblioevents' => $biblioevents, 'city' => $city, 'cities' => $cities, 'date' => $date, 'mon' => $mon, 'ads' => $ads, 'iuser' => $iuser ]);
	}

	// Промо событий в городе
	public function actionPromo($city, $date = false)
	{
		$this->layout='page';
		$city = Cities::find()->where('cities.alias = :a', [':a' => Yii::$app->request->get('city')])->one();
		$cities = Cities::Active();
		if (empty($city)) { return $this->render('404.twig'); }

		$mon = date("d.m.Y", strtotime("last Monday")); // Начало этой недели

		// Все события у которых есть активная дата и она не прошла
		$biblioevents = Biblioevents::find()->where(['biblioevents.city' => $city->id, 'biblioevents.status' => 1])->joinWith('oneactiveevent')->all();
		$bib_ids = array_unique(ArrayHelper::getColumn($biblioevents, 'id'));

		// Если дата PAST - все события БЕЗ активных дат
		if ($date == 'past') {
			$biblioevents = Biblioevents::find()->where(['city' => $city->id, 'status' => 1])->andWhere(['not in', 'id',  $bib_ids])->joinWith('places')->all();
		} elseif (!empty($date)) {
			$biblioevents = Biblioevents::find()->where(['biblioevents.city' => $city->id, 'biblioevents.status' => 1])
			->joinWith('events')->andWhere(['DATE(events.date)' => $date, 'events.status' => 1])->joinWith('places')->all();
		}

		$ads = Ads::City($city->id);

		$iuser = Yii::$app->user->identity;

		return $this->render('city-promo.twig', ['biblioevents' => $biblioevents, 'city' => $city, 'cities' => $cities, 'date' => $date, 'mon' => $mon, 'ads' => $ads, 'iuser' => $iuser ]);
	}

	// События в городе
	public function actionKultur($city, $date = false)
	{
		$this->layout='page';
		$city = Cities::find()->where('cities.alias = :a', [':a' => Yii::$app->request->get('city')])->one();
		$cities = Cities::Active();
		if (empty($city)) { return $this->render('404.twig'); }

		$mon = date("d.m.Y", strtotime("last Monday")); // Начало этой недели

		// Все события у которых есть активная дата и она не прошла
		$biblioevents = Biblioevents::find()->where(['biblioevents.city' => $city->id, 'biblioevents.status' => 1])->joinWith('oneactiveevent')->all();
		$bib_ids = array_unique(ArrayHelper::getColumn($biblioevents, 'id'));

		// Если дата PAST - все события БЕЗ активных дат
		if ($date == 'past') {
			$biblioevents = Biblioevents::find()->where(['city' => $city->id, 'status' => 1])->andWhere(['not in', 'id',  $bib_ids])->all();
		} elseif (!empty($date)) {
			$biblioevents = Biblioevents::find()->where(['biblioevents.city' => $city->id, 'biblioevents.status' => 1])
			->joinWith('events')->andWhere(['DATE(events.date)' => $date, 'events.status' => 1])->all();
		}

		$ads = Ads::City($city->id);

		$iuser = Yii::$app->user->identity;

		return $this->render('city-kr.twig', ['biblioevents' => $biblioevents, 'city' => $city, 'cities' => $cities, 'date' => $date, 'mon' => $mon, 'ads' => $ads, 'iuser' => $iuser ]);
	}

	// Список групп 
	public function actionBands( $status = 1)
	{
		$this->layout='page';
		
		
		if ($status== 'all') {
			$bands = Band::find()->all();
		} else {
			$bands = Band::find()->andwhere('status = :id', [':id' => $status])->all();
		}

		return $this->render('bands.twig', ['bands' => $bands]);
	}


		// Список фетивалей 
	public function actionFestivals( $status = 1)
	{
		$this->layout='page';
		
		
		if ($status== 'all') {
			$all = Festival::find()->all();
		} else {
			$all = Festival::find()->andwhere('status = :id', [':id' => $status])->all();
		}

		// echo "<pre>";
		// print_r($ads);
		// echo "</pre>";

		return $this->render('festivals.twig', ['all' => $all]);
	}

	// Список групп 
	public function actionSiteplaces()
	{
		$this->layout='page';
		$model = Places::find()->joinWith('cities')->andwhere('status > 0')->all();



		// echo "<pre>";
		// print_r($ads);
		// echo "</pre>";

		return $this->render('places-all.twig', ['model' => $model]);
	}

	// Страница
	public function actionPage($alias)
	{
		$this->layout='page';

		if (is_numeric($alias)) {
			$page = Page::find()->Where('id = :id', [':id' => $alias])->one();
		} else {
			$page = Page::find()->Where('alias = :alias', [':alias' => $alias])->one();
		}
		if (empty($page)) { return $this->render('404.twig'); }
		if (!empty($page->seotitle)) {
			Yii::$app->view->title = $page->seotitle; 
		} else {
			Yii::$app->view->title = $page->name; 
		}

		$guest= Yii::$app->user->isGuest;
		return $this->render('page.twig', ['page' => $page, 'guest' => $guest]);
	}

	public function actionNewsmakertask($id)
	{
		$this->layout='page';

		 //echo "<pre>";  print_r($id); echo "</pre>"; die;

		if (is_numeric($id)) {
			$model = NewsmakersEvents::find()->Where('id = :id', [':id' => $id])->one();
		} 
		if (empty($model)) { return $this->render('404.twig'); }
		
		return $this->render('newsmakertask.twig', ['model' => $model]);
	}


	// Раздел в городе
	public function actionSection($city, $section)
	{
		$this->layout='page';
		$city = Cities::find()->where(['alias' => Yii::$app->request->get('city')])->one();
		$cities = Cities::Active();
		if (empty($city)) { return $this->render('404.twig'); }

		$sec = Section::find()->where(['url' => $section])->one();
		if (empty($sec)) { return $this->render('404.twig'); }

		$biblioevents = Biblioevents::find()
		->where('biblioevents.city = :city', [':city' => $city->id])
		->andWhere('biblioevent_section.section_id = :section', [':section' => $sec->id])
		->andWhere(['biblioevents.status' => 1])
		->joinWith('biblioeventSection')
		->joinWith('oneactiveevent')
		->all();

		$ads = Ads::Section($sec->id, $city->id);

		// echo "<pre>";
		// print_r($biblioevents);
		// echo "</pre>";

		return $this->render('section.twig', ['biblioevents' => $biblioevents, 'city' => $city, 'section' => $sec, 'cities' => $cities, 'ads' => $ads]);
	}

	// Все события города
	public function actionAllevents($city, $section=False)
	{
		$this->layout='page';
		$city = Cities::find()->where(['alias' => Yii::$app->request->get('city')])->one();
		$cities = Cities::Active();
		if (empty($city)) { return $this->render('404.twig'); }

		$sec = Section::find()->where(['url' => $section])->one();
		if (empty($sec)) { return $this->render('404.twig'); }

		$biblioevents = Biblioevents::find()
		->where('biblioevents.city = :city', [':city' => $city->id])
		->andWhere(['biblioevents.status' => 1])
		->all();

		$ads = Ads::Section($sec->id, $city->id);

		return $this->render('section.twig', ['biblioevents' => $biblioevents, 'city' => $city, 'section' => $sec, 'cities' => $cities, 'ads' => $ads]);
	}

	// Лендинг для события
	public function actionLanding($city, $alias, $t = false)
	{
		$this->layout='page-landing';

		$city = Cities::find()->where('cities.alias = :a', [':a' => $city])->one();
		$cities = Cities::Active();
		if (empty($city)) { return $this->render('404.twig'); }

		if (is_numeric($alias)) {
			$biblioevent = Biblioevents::find()
			->joinWith([
				'events' => function ($query) {
					$query->onCondition('DATE(events.date) >= DATE(NOW())');
				}])
			->joinWith('places')
			->joinWith('img')
			->joinWith('landing') 
			->joinWith('sections')
			->joinwith('posts') 
			->joinWith('company')
			->where('biblioevents.city = :city', [':city' => $city->id])
			->andWhere('biblioevents.id = :id', [':id' => $alias])
			->one();
		} else {
			$biblioevent = Biblioevents::find()
			->joinWith([
				'events' => function ($query) {
					$query->onCondition('DATE(events.date) >= DATE(NOW())');
				}])
			->joinWith('places')
			->joinWith('landing')
			->joinWith('sections')
			->joinWith('company')
			->where('biblioevents.city = :city', [':city' => $city->id])
			->andWhere('biblioevents.alias = :alias', [':alias' => $alias])
			->one();
		}
		if (empty($biblioevent) or $biblioevent->status == -1 ) { return $this->render('404.twig'); }
		if (!empty($biblioevent->landing->seotitle)) {
			Yii::$app->view->title = $biblioevent->landing->seotitle; 
		} else {
			Yii::$app->view->title = $biblioevent->name.' '.$city->name; 
		}
		$ads = Ads::Event($biblioevent->id);
		
		if ($t == 'a') {
			$events = Events::find()
			->joinWith('seats')
			->where('events.event_id = :eid', [':eid' => $biblioevent->id])
			->andwhere('DATE(date) >= DATE(NOW())')
			->andwhere('status = 1')
			->andwhere(['>', 'seats.count', '0'])
			->orderBy('date')->all();
			return $this->render('landdates.twig', ['events' => $events, 'biblioevent' => $biblioevent, 'cities' => $cities]);

		} elseif ($t > 0) {

			$event = Events::find()
			->where(['events.event_id' => $biblioevent->id, 'id' => $t])
			->andwhere('DATE(date) >= DATE(NOW())')
			->andwhere('status = 1')
			->one();

			if ($event->date >= date("Y-m-d")) {
				$seats = Seats::find()->where(['event_id' => $event->id])->andWhere(['or',['promocode' => null],['promocode' => ''],])->andWhere('row is null')->all();
				foreach ($seats as $seat) {
					$tickets = Tickets::find()->where(['seat_id' => $seat->id])->andWhere('status > 0')->sum('count');
					$count = $seat->count - $tickets;
					if ($count > 0) {
						$newseats[] = $seat;
					}
				}

				$s_sort = Seats::SortSeats($event);
				$color = Seats::ColorSeats($event);

				// Есть ли билет с промокодом
				$promo = Seats::find()->where(['event_id' => $event->id])->andWhere(['not', ['promocode' => null]])->andWhere(['not', ['promocode' => '']])->all();
				if (empty($promo)) { $promo = null;}

				return $this->render('landseats.twig', [
					'seats' => $newseats,
					'biblioevent' => $biblioevent,
					'event' => $event,
					'promo' => $promo,
					's_sort' => $s_sort,
					'color' => $color
				]);
			}
		}
		
		$event = Events::find()
		->where(['events.event_id' => $biblioevent->id])
		->andwhere('DATE(date) >= DATE(NOW())')
		->andwhere('status = 1')->orderBy(['date' => SORT_ASC])
		->one();

		// echo "<pre>";
			// print_r($event);
			// echo "</pre>";die;

		if ($event->id ?? Null){
			$bands = BandEvent::find()->where(['event_id' => $event->id])->joinWith('band')->all();
			

		// echo "<pre>";
			// print_r($event);
			// echo "</pre>";die;

			$brandsIds = Items::find()
			    ->select('item_id')
			    ->where([
			        'usecase' => 'event',
			        'item' => 'brand', 
			        'usecase_id' => $event->id
			    ])
			    ->column();

			$brands = Brands::find()->andwhere(['id' => $brandsIds])->with(['post.imgs'])->all();

			// echo "<pre>";
			// print_r($brandsIds);
			// echo "</pre>";die;

			$posts = Posts::find()
			    ->where([
			        'item' => 'event',
			        'item_id' => $event->id,
			        
			    ])->orwhere([
			        'usecase' => 'post',
			        'item' => 'biblioevent', 
			        'item_id' => $biblioevent->id
			    ])->joinWith(['imgs'])
			    ->asArray()
			    ->all();

			   // echo "<pre>";
			// print_r($post);
			// echo "</pre>";die;


		} else{
			$bands = Null;
		}


		// echo "<pre>";
			// print_r($post);
			// echo "</pre>";die;

		$biblioevent_one = $biblioevent_all  = Null;
		
		if (!empty(Yii::$app->user->id)) {
			// Проверка является ли этот юзер админом этого библиоевента 
			$ids = Companies::getIds(); // получение всех id кабинетов юзера
			$biblioevent_all = Biblioevents::find()->where(['company_id' => $ids])->joinwith('events')->asArray()->all(); // получение всех библиоевентов вместе с датами из всех компаний
			$biblioevent_one = Biblioevents::find()->where(['company_id' => $ids,'biblioevents.id'=> $biblioevent->id])->joinwith('events')->one(); // получаем все даты текущего библиевента, если юзер организатор
		}
		$event_link   = Null;

		$event_link = Biblioevents::find()->where(['biblioevents.id'=> $biblioevent->id])
		->joinWith([
			'events' => function ($query) {
				$query->onCondition('DATE(events.date) >= DATE(NOW())');
			}])->one(); 

		$place = Places::find()
		->where(['places.id' => $biblioevent->place_id])
		->joinwith('posts')
		->one();

		//Vitalhit::pre($place);

		$iuser = Yii::$app->user->identity;

		$qrurl = 'https://igoevent.com/'.$city->alias.'/event/'.$alias.'?utm_source=partner&utm_medium='.(($iuser)?$iuser->utm_medium:'igoevent').'&utm_campaign=qr&utm_content='.$alias.'&utm_term=b'.$biblioevent->id ;
		$qrCode = (new QrCode($qrurl))
		->setSize(250)
		->setMargin(5)
		->useForegroundColor(70, 10, 90);

		$qr = $qrCode->writeDataUri();



		

		$landing_template = 'landing.twig';
		
		return $this->render($landing_template, ['biblioevent' => $biblioevent, 'cities' => $cities, 'biblioevent_one' => $biblioevent_one, 'ads' => $ads, 'event_link' => $event_link, 'iuser'=> $iuser, 'place'=>$place, 'qr' => $qr , 'bands' => $bands , 'brands'=> $brands??Null, 'posts'=>$posts??Null]);
	}

	public function actionLandingpost($city = false, $alias = false, $t = false)
		{
				// разработка
			    $posts = Posts::find()
			    ->where([
			        'item' => 'event',
			        'item_id' => 6217
			    ])->joinWith(['imgs'])
			    ->asArray()
			    ->all();

			    echo "<pre>";
				print_r($posts);
				echo "</pre>";
			
		}


	// Лендинг для события // не доделано // не исползуется
	public function actionEvent($city, $alias, $id, $t = false)
	{
		$this->layout='page';

		$date =  Events::find()->where(['events.id' => $id ])->one();

		$biblioevent = Biblioevents::find()->where(['id'=>$date->event_id])->one();

		$city = Cities::find()->where('cities.id = :a', [':a' => $biblioevent->city])->one();

		// echo "<pre>";
		// print_r($biblioevent);
		// echo "</pre>";

		return $this->render($landing_template, ['biblioevent' => $biblioevent, 'cities' => $cities, 'biblioevent_one' => $biblioevent_one, 'ads' => $ads, 'event_link' => $event_link, 'iuser'=> $iuser, 'place'=>$place, 'qr' => $qr , 'bands' => $bands]);
	}


	// Лендинг для Бенда
	public function actionBand($alias)
	{
		$this->layout='page';

		if (is_numeric($alias)) {
			$band = Band::find()
			->andWhere('band.id = :id', [':id' => $alias])
			->joinWith('activeevents')
			->joinWith('activeevents.biblioevents')
			->joinWith('landing')
			->one();
		} else {
			$band = Band::find()
			->andWhere('band.alias = :alias', [':alias' => $alias])
			->joinWith('activeevents')
			->joinWith('activeevents.biblioevents')
			->joinWith('landing')
			->one();
		}
		if (empty($band)) {
			$band = Band::find()
			->andWhere('band.alias = :alias', [':alias' => $alias])
			->joinWith('landing')
			->one();
		}
		if (empty($band)) { return $this->render('404.twig'); }
		if (!empty($band->landing->seotitle)) {
			Yii::$app->view->title = $band->landing->seotitle; 
		} else {
			Yii::$app->view->title = $band->name; 
		}

		// echo "<pre>";
		// print_r($band);
		// echo "</pre>";

		$biblioevents = BiblioeventBand::find()->where(['biblioevent_band.band_id' => $band->id])->joinWith('biblioevents')->all();

		$bib_ids = array_unique(ArrayHelper::getColumn($biblioevents, 'biblioevent_id'));

		// echo "<pre>";
		// print_r($bib_ids);
		// echo "</pre>";die;
		
		$events = [];

		$events = Events::find()->where(['biblioevent_id' => $bib_ids])->joinWith('biblioevents')->andwhere('DATE(date) >= DATE(NOW())')->orderBy(['date' => SORT_DESC])->all();

		 ArrayHelper::multisort($events, ['date'], [ SORT_DESC]);
		//$events = $events->orderBy(['events.date' => SORT_ASC]); 
		// ->andwhere('DATE(date) >= DATE(NOW())')

		


		return $this->render('band.twig', ['band' => $band, 'biblioevents' => $biblioevents, 'events' => $events]);
	}

	// Лендинг для Райдер
	public function actionRider($alias)
	{
		$this->layout='page';

		if (is_numeric($alias)) {
			$band = Band::find()
			->andWhere('band.id = :id', [':id' => $alias])
			->joinWith('activeevents')
			->joinWith('activeevents.biblioevents')
			->joinWith('landing')
			->one();
		} else {
			$band = Band::find()
			->andWhere('band.alias = :alias', [':alias' => $alias])
			->joinWith('activeevents')
			->joinWith('activeevents.biblioevents')
			->joinWith('landing')
			->one();
		}
		if (empty($band)) {
			$band = Band::find()
			->andWhere('band.alias = :alias', [':alias' => $alias])
			->joinWith('landing')
			->one();
		}
		if (empty($band)) { return $this->render('404.twig'); }
		if (!empty($band->landing->seotitle)) {
			Yii::$app->view->title = $band->landing->seotitle; 
		} else {
			Yii::$app->view->title = $band->name; 
		}

		// echo "<pre>";
		// print_r($band);
		// echo "</pre>";

		return $this->render('rider.twig', ['band' => $band]);
	}


// Лендинг для Бенда
	public function actionTodo($alias)
	{
		$this->layout='page';

		if (is_numeric($alias)) {
			$band = Band::find()
			->andWhere('band.id = :id', [':id' => $alias])
			->joinWith('activeevents')
			->joinWith('activeevents.biblioevents')
			->joinWith('landing')
			->one();
		} else {
			$band = Band::find()
			->andWhere('band.alias = :alias', [':alias' => $alias])
			->joinWith('activeevents')
			->joinWith('activeevents.biblioevents')
			->joinWith('landing')
			->one();
		}
		if (empty($band)) {
			$band = Band::find()
			->andWhere('band.alias = :alias', [':alias' => $alias])
			->joinWith('landing')
			->one();
		}
		if (empty($band)) { return $this->render('404.twig'); }
		if (!empty($band->landing->seotitle)) {
			Yii::$app->view->title = $band->landing->seotitle; 
		} else {
			Yii::$app->view->title = $band->name; 
		}

		// echo "<pre>";
		// print_r($band);
		// echo "</pre>";

		$biblioevents = BiblioeventBand::find()->where(['biblioevent_band.band_id' => $band->id])->joinWith('biblioevents')->all();

		// echo "<pre>";
		// print_r($biblioevents);
		// echo "</pre>";


		return $this->render('band-todo.twig', ['band' => $band, 'biblioevents' => $biblioevents]);
	}


	// Лендинг для места
	public function actionPlace($city, $alias)
	{
		$this->layout='page';
		$city = Cities::find()->where('cities.alias = :a', [':a' => Yii::$app->request->get('city')])->one();
		if (empty($city)) { return $this->render('404.twig'); }

		if (is_numeric($alias)) {
			$place = Places::find()->where('places.id = :alias', [':alias' => $alias])
			->andWhere('places.city = :city', [':city' => $city->id])
			->joinWith('cities')
			->joinWith('landing')
			->joinWith('posts')
			->one();
			if (!empty($place->alias)) {
				$this->redirect('/'.$city->alias.'/place/'.$place->alias, 301);
			}
		} else {
			$place = Places::find()->where('places.alias = :alias', [':alias' => $alias])
			->andWhere('places.city = :city', [':city' => $city->id])
			->joinWith('cities')
			->joinWith('landing')
			->joinWith('posts')
			->one();


		}
		if (empty($place)) { return $this->render('404.twig'); }

		$biblioevents = Biblioevents::find()
		// ->where('biblioevents.city = :city', [':city' => $city->id]) - достаточно проверки по месту
		->where('biblioevents.place_id = :id', [':id' => $place->id])
		->andWhere('biblioevents.status = 1')
		->joinWith('activeevents') // oneactiveevent change to activeevents 210908
		->joinWith('categoryevents')
		->joinWith('img')
		->all();
		

		$ads = Ads::Place($place->id);

		// echo "<pre>";
		// print_r($events);
		// echo "</pre>";

		return $this->render('place.twig', ['biblioevents' => $biblioevents, 'city' => $city, 'place' => $place, 'ads' => $ads]);
	}


	public function actionAgreement($id)
	{
		$event = Biblioevents::find()->joinWith('places')->where('biblioevents.id = :biblio', [':biblio' => $id])->one();
		return $this->render('agreement.twig', ['event' => $event]);
	}




	// 404
	public function action404()
	{
		if (isset(Yii::$app->user->id)) {
			$user = Yii::$app->user->id;
		} else {
			$user = null;
		}
		return $this->render('404.twig', ['user' => $user]);
	}


	// FeedBack форма
	public function actionFeedback()
	{
		if($_POST) {
			$feed = new Feedback();
			$feed->page = $_POST['page'];
			$feed->user_id = $_POST['user_id'];
			$feed->task_id = $_POST['task_id'];
			$feed->text = $_POST['text'];
			$feed->for_user_id = $_POST['for_user_id'];
			$feed->status = 1;
			if ($feed->save()) {
				return '<h2 class="mt100">Спасибо за ваше обращение!</h2>';
			}else {
				LogPage::setLog('site/feedback', 2, 'НЕТ: '.json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
				return '<h2>Неудача!</h2>';
			}
		}
	}


	// ВСЕ картинки для переиспользования
	public function actionAllimg($id)
	{
		$img = Img::find()->orderBy(['id' => SORT_DESC])->all();
		return $this->renderPartial('img.twig', ['img' => $img, 'id' => $id]);
	}

	public function actionAllimgband($id)
	{
		$img = Img::find()->orderBy(['id' => SORT_DESC])->all();
		return $this->renderPartial('imgband.twig', ['img' => $img, 'id' => $id]);
	}

	public function actionAllimglanding($id)
	{
		$img = Img::find()->orderBy(['id' => SORT_DESC])->all();
		return $this->renderPartial('imglanding.twig', ['img' => $img, 'id' => $id]);
	}
	
	public function actionAllimglandingimg($id, $imageid = fales)
	{
		$img = Img::find()->orderBy(['id' => SORT_DESC])->all();
		return $this->renderPartial('imglandingimg.twig', ['img' => $img, 'id' => $id, 'imageid' => $imageid]);
	}

	// Установим картинк по Ajax
	public function actionSetimg($image, $id)
	{
		file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - имг ').json_encode($image), FILE_APPEND);
		file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - имг айди ').json_encode($id), FILE_APPEND);

		$biblio = Biblioevents::findOne($id);
		$biblio->image = $image;
		if ($biblio->save()) {
		} else {
			file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - установить картинку ошибка ').json_encode($biblio->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
		}
		return $this->redirect('/crm/biblioevents/update?id='.$id);
	}


	// Установим картинк по Ajax
	public function actionSetimgband($image, $id)
	{
		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - имг ').json_encode($image), FILE_APPEND);
		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - имг айди ').json_encode($id), FILE_APPEND);

		$bandone = Band::findOne($id);
		$bandone->image = $image;
		if ($bandone->save()) {
		} else {
			file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - установить картинку ошибка ').json_encode($bandone->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
		}
		return $this->redirect('/crm/band/update?id='.$id);
	}

	// Установим картинк по Ajax
	public function actionSetimglanding($image, $id)
	{
		$model = Landing::findOne($id);	
		$model->image = $image;
		if ($model->save()) {
		} else {
			file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - установить картинку ошибка ').json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
		}
		return $this->redirect('/crm/landing/update?id='.$id);
	}

	public function actionSetimglandingimg($image, $id, $imageid = false)
	{
		$model = Landing::findOne($id);	
		if ($imageid){ $model['image'.$imageid] = $image;}
		if ($model->save()) {
		} else {
			file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - установить картинку ошибка ').json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
		}
		return $this->redirect('/crm/landing/update?id='.$id);
	}



	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		}
		return $this->render('contact', ['model' => $model,]);
	}



	public function actionAbout()
	{
		return $this->render('about');
	}



	public function actionFaq()
	{
		return $this->render('faq');
	}



	// Просмотр и печать купленного билета гостем
	public function actionTicket($barcode = false, $id = false)
	{
		$this->layout='site';
		Yii::$app->view->title = 'Распечатать билет'; 
		if (!empty($barcode)) {
			$ticket = Tickets::find()->joinWith('events')->joinWith('seats')
			->where('barcode = :code', [':code' => $barcode])->andWhere(['tickets.del' => 0])->one();
		} elseif (!empty($id)){
			$ticket = Tickets::find()->joinWith('events')->joinWith('seats')
			->where('order_id = :order_id', [':order_id' => $id])->andWhere(['tickets.del' => 0])->one();
		} else {
			return $this->render('404.twig');	
		}
		
		// if (empty($ticket->barcode)) { return $this->render('404.twig'); }

		$date = Events::findOne($ticket->event_id);
		$event = Biblioevents::find()->joinWith('places')->where(['biblioevents.id' => $date->event_id])->one();
		$person = Persons::findOne($ticket->user_id);
		$eventplace = Places::find()->where(['places.id' => $date->place_id])->one();
		$value = $ticket->barcode;

		$barc = array(
			'elementId'=> 'ticketBarcode',
			'value'=> $value,
			'type'=>'code128' /*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/
		);

		$qrCode = (new QrCode('https://igoevent.com/site/ticket?barcode='.$ticket->barcode))
		->setSize(250)
		->setMargin(5)
		->useForegroundColor(70, 10, 90);

		$qr = $qrCode->writeDataUri();

		// echo $barc['value'];
		// echo "<pre>";
		// print_r($barc['value']);
		// echo "</pre>";

		if ($ticket->template_id == 9){
			return $this->render('ticket9.twig', ['ticket' => $ticket, 'person' => $person, 'event' => $event, 'qr' => $qr, 'barc' => $barc, 'eventplace' => $eventplace]);
		}

		return $this->render('ticket.twig', ['ticket' => $ticket, 'person' => $person, 'event' => $event, 'qr' => $qr, 'barc' => $barc, 'eventplace' => $eventplace]);
	}

	// Просмотр и печать купленного билета гостем
	public function actionTicket2($barcode = false, $id = false)
	{
		$this->layout='site';
		Yii::$app->view->title = 'Распечатать билет'; 
		if (!empty($barcode)) {
			$ticket = Tickets::find()->joinWith('events')->joinWith('seats')
			->where('barcode = :code', [':code' => $barcode])->andWhere(['tickets.del' => 0])->one();
		} elseif (!empty($id)){
			$ticket = Tickets::find()->joinWith('events')->joinWith('seats')
			->where('order_id = :order_id', [':order_id' => $id])->andWhere(['tickets.del' => 0])->one();
		} else {
			return $this->render('404.twig');	
		}
		
		// if (empty($ticket->barcode)) { return $this->render('404.twig'); }

		$date = Events::findOne($ticket->event_id);
		$event = Biblioevents::find()->joinWith('places')->where(['biblioevents.id' => $date->event_id])->one();
		$person = Persons::findOne($ticket->user_id);

		$value = $ticket->barcode;

		$barc = array(
			'elementId'=> 'ticketBarcode',
			'value'=> $value,
			'type'=>'code128' /*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/
		);

		$qrCode = (new QrCode('https://igoevent.com/site/ticket?barcode='.$ticket->barcode))
		->setSize(250)
		->setMargin(5)
		->useForegroundColor(70, 10, 90);

		$qr = $qrCode->writeDataUri();

		// echo $barc['value'];
		// echo "<pre>";
		// print_r($barc['value']);
		// echo "</pre>";

		return $this->render('ticket2.twig', ['ticket' => $ticket, 'person' => $person, 'event' => $event, 'qr' => $qr, 'barc' => $barc]);
	}


	// Оплата неоплаченного билета
	public function actionPay($order_id)
	{
		$this->layout='site';
		Yii::$app->view->title = 'Оплатить билет'; 

		$tickets = Tickets::find()->joinWith('events')->joinWith('seats')->joinWith('ticketCategory')
		->where('order_id = :order_id', [':order_id' => $order_id])->andWhere(['tickets.del' => 0])->all();

		if (empty($tickets)) {
			throw new \yii\web\NotFoundHttpException('Заказ не найден.');
		}

		$sum = Tickets::find()->where('order_id = :order_id', [':order_id' => $order_id])->andWhere(['tickets.del' => 0])->sum('summa');
		$event = Events::findOne($tickets[0]->event_id);
		$user = $tickets[0]->user_id ? Persons::findOne($tickets[0]->user_id) : null;
		if ($user === null) {
			$t = $tickets[0];
			$user = (object)[
				'name' => $t->customer_name ?: $t->name ?: '',
				'second_name' => $t->secondname ?: '',
				'mail' => $t->customer_email ?: $t->email ?: '',
				'phone' => $t->customer_phone ?: $t->phone ?: '',
				'id' => 0,
			];
		}

		// Есть ли место которое нельзя оплатить (для билетов с seats; экскурсии без seat_id — оплата разрешена)
		$afterpay = 1;
		foreach ($tickets as $ticket) {
			if ($ticket->seats !== null && $ticket->seats->afterpay == 0) {
				$afterpay = 0;
				break;
			}
		}

		// Если открыли из письма с напоминанием - то поставим этому напоминанию статус = 2
		$message_id = Yii::$app->request->get('message_id');
		if (isset($message_id)) {
			$messge = Messages::findOne($message_id);
			$messge->status = 2;
			$messge->save();
		}

			// echo '<pre>';
			// print_r($event);
			// echo '</pre>';

		return $this->render('pay.twig', ['tickets' => $tickets, 'afterpay' => $afterpay, 'sum' => $sum, 'user' => $user, 'event' => $event, 'order_id' => $order_id]);
	}




	// Проверка просроченных броней и перевод в статус = 0
	public function actionAfterpay()
	{
		$now = strtotime(date('d-m-Y H:i:s'));
		// $tickets = Tickets::find()->joinWith('seats')->where('tickets.status = 1 AND tickets.summa > 0')->andWhere('seats.afterpay = 0')->limit(100)->all();
		$tickets = Tickets::find()->where('tickets.status = 1 AND tickets.summa > 0')->limit(10)->all();
		$ids = null;
		$i = 0;
		foreach ($tickets as $ticket) {
			// Окончание брони. К покупке билета прибавляем 30 минут
			$end = strtotime($ticket->date) + 1800; 
			if ($now > $end) {
				$ticket->status = 0;
				$ticket->save();
				$i++;
				$ids[] = $ticket->id;
				//echo $ticket->id." - ".$now." > ".$end." Да!<br>";
			}else{
				//echo $ticket->id." - ".$now." < ".$end." НЕТ!<br>";
			}
		}
		$logCron = LogCron::setLog('afterpay',$i.' броней снято, id: '.json_encode($ids), 1);
	}




	// Оплата неоплаченной аренды
	public function actionPayrent($order_id)
	{
		$this->layout='site';
		Yii::$app->view->title = 'Оплатить бронь аренды'; 

		$rents = Rents::find()->joinWith('rooms')->where('order_id = :order_id', [':order_id' => $order_id])->all();
		$sum = Rents::find()->where('order_id = :order_id', [':order_id' => $order_id])->sum('summa');
		$room = Rooms::findOne($rents[0]->room_id);
		$user = Persons::findOne($rents[0]->person_id);

			// Если открыли из письма с напоминанием - то поставим этому напоминанию статус = 2
		$message_id = Yii::$app->request->get('message_id');
		if (isset($message_id)) {
			$messge = Messages::findOne($message_id);
			$messge->status = 2;
			$messge->save();
		}
		return $this->render('payrent.twig', ['rents' => $rents, 'sum' => $sum, 'user' => $user, 'room' => $room, 'order_id' => $order_id]);
	}


	// Список мест в городе с картинками
	public function actionPlaces($city)
	{
		$this->layout='page';
		$city = Cities::find()->where('cities.alias = :a', [':a' => Yii::$app->request->get('city')])->one();
		if (empty($city)) { return $this->render('404.twig'); }

		$places = Places::find()->where('places.city = :city', [':city' => $city->id])->andWhere('places.status = 1')->all();

		// echo "<pre>";
		// print_r($pl);
		// echo "</pre>";
		foreach ($places as $place) {
			$bib = Biblioevents::find()->where(['place_id' => $place->id, 'biblioevents.status' => 1])->count();
			if ($bib >= 0) { 
				$pl[] = array('place' => $place, 'bib' => $bib );
			}
		}

		$b = array_column($pl, 'bib');

		array_multisort($b, SORT_DESC, $pl);

		// echo "<pre>";
		// print_r($pl);
		// echo "</pre>";

		return $this->render('places.twig', ['pl' => $pl, 'city' => $city]);
	}

// Список мест в городе
	public function actionPlaceslist($city)
	{
		$this->layout='page';
		$city = Cities::find()->where('cities.alias = :a', [':a' => Yii::$app->request->get('city')])->one();
		if (empty($city)) { return $this->render('404.twig'); }

		$places = Places::find()->where('places.city = :city', [':city' => $city->id])->orderBy(['standing'=>SORT_DESC, 'standing_max'=>SORT_DESC])->andWhere('places.status = 1')->all();

		// echo "<pre>";
		// print_r($pl);
		// echo "</pre>";
		foreach ($places as $place) {
			$bib = Biblioevents::find()->where(['place_id' => $place->id, 'biblioevents.status' => 1])->count();
			if ($bib >= 0) { 
				$pl[] = array('place' => $place, 'bib' => $bib );
			}
		}

		$b = array_column($pl, 'bib');

		//array_multisort($b, SORT_DESC, $pl);

		// echo "<pre>";
		// print_r($pl);
		// echo "</pre>";

		return $this->render('placeslist.twig', ['pl' => $pl, 'city' => $city]);
	}


	







	// Оферта компании
	public function actionOferta($id)
	{
		$this->layout='site';
		$event = Biblioevents::find()->where(['biblioevents.id' => $id])->joinWith('company')->one();
		$event2 = Companies::findOne($id);
		// echo "<pre>";
		// print_r($event);
		// echo "</pre>";
		return $this->render('oferta.twig', ['event' => $event]);
	}



	public function actionEvents()
	{
		$biblioevents = Biblioevents::find()->joinWith('events')->andwhere('DATE(date) >= DATE(NOW())')->orderBy(['date'=>SORT_ASC])->all();
		return $this->render('events.twig', ['biblioevents' => $biblioevents]);
	}


	public function actionDate($id)
	{
		$id = Yii::$app->request->get('id');
		$events = Events::find()->joinWith('biblioevents')->where(['biblioevents.id' => $id])->andwhere('DATE(date) >= DATE(NOW())')->orderBy(['date'=>SORT_ASC])->all();
		return $this->render('date.twig', ['events' => $events]);
	}




	public function actionForm($id)
	{
		$event = Events::findOne($id);
		if ($event->date >= date("Y-m-d H:i:s")) {

			$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
			$seats = Seats::find()->where(['event_id' => $event->id])->all();

			return $this->render('form.twig', ['event' => $event, 'seats' => $seats]);
		} else {
			return $this->render('form.twig');
		}

	}








	// НОВОЕ API
	// ------------------------------------------------------------------------------

	// Получаем даты в событии
	public function actionApiDates($id)
	{
		$id = Yii::$app->request->get('id');
		$events = Events::find()
		->joinWith('seats')
		->where('events.event_id = :eid', [':eid' => $id])
		->andwhere('DATE(date) >= DATE(NOW())')
		->andwhere('status = 1')
		->andwhere(['>', 'seats.count', '0'])
		->orderBy('date')->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $this->renderPartial('api_dates.twig', ['events' => $events]);
	}







	// API
	// ------------------------------------------------------------------------------





	// Получаем даты в событии
	public function actionDateapi($id)
	{
		$id = Yii::$app->request->get('id');
		$events = Events::find()
		->joinWith('seats')
		->where('events.event_id = :eid', [':eid' => $id])
		->andwhere('DATE(date) >= DATE(NOW())')
		->andwhere('status = 1')
		->andwhere(['>', 'seats.count', '0'])
		->orderBy('date')->all();
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $events;
	}



	public function actionFormapi($id)
	{
		$event = Events::findOne($id);
		if ($event->date >= date("Y-m-d H:i:s")) {

			$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
			$seats = Seats::find()->where(['event_id' => $event->id])->all();
			foreach ($seats as $seat) {
				$tickets = Tickets::find()->where(['seat_id' => $seat])->sum('count');
				$count = $seat->count - $tickets;
				if ($count > 0) {
					$newseats[] = $seat;
				}
			}

			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return ['seats' => $newseats, 'event' => $event, 'scheme' => $biblioevent->image];
		} else {
			return $this->renderPartial('formframe.twig');
		}

	}



	public function actionFormnewapi($id)
	{
		$event = Events::findOne($id);
		if ($event->date >= date("Y-m-d H:i:s")) {

			$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
			$seats = Seats::find()->where(['event_id' => $event->id])->all();
			foreach ($seats as $seat) {
				$tickets = Tickets::find()->where(['seat_id' => $seat])->sum('count');
				$count = $seat->count - $tickets;
				if ($count > 0) {
					$newseats[] = $seat;
				}
			}

			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return $this->renderPartial('formnewapi.twig', [
				'seats' => $newseats,
				'event' => $event,
				'scheme' => $biblioevent->image
			]);
		}

	}



	// При покупке билетов получаем информацию о доступных местах - непроданных билетах
	public function actionCountseatapi($userticket, $seat, $eventid)
	{
		$seat = Yii::$app->request->get('seat');
		$seats = Seats::find()->where(['id' => $seat])->one();
		if ($seats) {
			$tickets = Tickets::find()->where(['seat_id' => $seat])->sum('count');
		} else{
			$tickets = 0;
		}

		$count = $seats->count - $tickets;

		if ($count > 0 and $userticket>0) {
			if ($userticket >= $count) {
				$count = $count;
			} else {
				$count = $userticket;
			}
		}else {
			$count = 0;
		}

		return $this->renderPartial('countseats.twig', ['count' => $count]);
	}


	// При покупке билетов проверяем есть ли у нас такой гость по mail
	public function actionUserisapi($mail)
	{
		$mail = Yii::$app->request->get('mail');
		$user = Persons::find()->where(['mail' => $mail])->one();

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $user;
	}










	// --------------------------------
	//             API
	// --------------------------------



	// БЕСПЛАТНАЯ регистрация через форму на стороннем сайте
	public function actionBookingapi()
	{

		$smena = Smena::find()->where(['type' => 1])->andWhere('end is Null')->one();

		if($_POST) {
			$book = new Bookingapi();

			$book->from_url = $_POST['from_url'];
			$book->name = $_POST['name'];
			$book->mail = $_POST['mail'];
			$book->phone = $_POST['phone'];
			$book->message = $_POST['message'];
			$book->utm_source = $_POST['utm_source'];
			$book->utm_medium = $_POST['utm_medium'];
			$book->utm_campaign = $_POST['utm_campaign'];
			$book->utm_content = $_POST['utm_content'];
			$book->utm_term = $_POST['utm_term'];
			$book->smena_id = $smena->id;
			if ($owner_id) {
				$book->owner_id = $smena->user_id;
			}else {
				$book->owner_id = 0;
			}
			$book->status_id = 1;

			$book->save();	

			$return = 'Спасибо за регистрацию!';
		} else {
			$return = 'Неудача!';
		}

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $return;

	}






	// --------------------------------------
	// ПОКУПКА билета по API с других сайтов!


	public function actionTicketbuyapi(){

		file_put_contents('/home/v/vitalhit/newcrm/public_html/web/uploads/SITE.txt', PHP_EOL . Date('d.m.Y H:i:s') . ' - ТикетЯндекс Апи - ПОСТ - '. json_encode($_POST), FILE_APPEND);

		// Проверка есть ли такой гость по mail
		if(isset($_POST['id']) && !empty($_POST['id'])) {
			//$person = Persons::findPersonById($_POST['id']);
			$person = Persons::findOne($_POST['id']);
		} else { // Создаем нового гостя
			$person = Persons::createPerson($_POST);
		}
		$pid = $person->id; 
		$info = $_POST['info']; // Комментарий гостя к билету

		// Перебираем места, которые выбрал гость
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
					$ticket->type = $_POST['type']; // Оплата через яндекс кассу без вариантов
					$ticket->from_url = $_POST['from_url'];
					if($_POST['subscribe'] == 'on') { $ticket->subscribe = 1;}
					if($_POST['field1']) { $ticket->field1 = $_POST['field1'];}
					if($_POST['field2']) { $ticket->field2 = $_POST['field2'];}
					if($_POST['field3']) { $ticket->field3 = $_POST['field3'];}
					if($_POST['field4']) { $ticket->field4 = $_POST['field4'];}
					if($_POST['field5']) { $ticket->field5 = $_POST['field5'];}
					if($_POST['field6']) { $ticket->field6 = $_POST['field6'];}
					if($_POST['field7']) { $ticket->field7 = $_POST['field7'];}
					if($_POST['field8']) { $ticket->field8 = $_POST['field8'];}
					if($_POST['field9']) { $ticket->field9 = $_POST['field9'];}
					if($_POST['field10']) { $ticket->field10 = $_POST['field10'];}
					$ticket->utm_source = $_POST['utm_source'];
					$ticket->utm_medium = $_POST['utm_medium'];
					$ticket->utm_campaign = $_POST['utm_campaign'];
					$ticket->utm_content = $_POST['utm_content'];
					$ticket->utm_term = $_POST['utm_term'];
					$ticket->status = 1;

					if($ticket->save()) {
						// Получаем название события, чтобы передать в письмо и в спасибо!
						$event = Events::find()->joinWith('biblioevents')->joinWith('biblioevents.places')->joinWith('biblioevents.letterBuy')->where(['events.id' => $ticket->event_id])->one();
						if (!empty($event->biblioevents->letterBuy->theme)) {
							$theme = $event->biblioevents->letterBuy->theme;
						}else{
							$theme = 'Спасибо за покупку! '.$buying_event.' '.$date;
						}
						$date = date("d.m.Y H:i", strtotime($event->date));

						$buying_event = $event->biblioevents->name;
						$seats = array( $ticket->id, $ticket->money, $ticket->count, $order_id );
						$buying_seats[] = $seats;

						$return = '<h2>Спасибо за покупку на '.$buying_event.'<br>'.$date.'.<br>На вашу почту отправлен электронный билет!</h2>';

						$sum = $sum + $ticket->summa; // Суммируем стоимость билетов для оплаты

					} else {
						$return = '<h2>Покупка не удалась, попробуйте еще раз!</h2>';
					}
				}
			};
		}

		$tickets = Tickets::find()->where(['order_id' => $order_id])->all();

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		// Если сумма > 0 - то оплата
		if ( $sum > 0) {
			return $this->renderPartial('apipay.twig', [
				'user' => $person,
				'order_id' => $order_id,
				'event' => $event,
				'info' => $ticket->info,
				'tickets' => $tickets,
				'sum' => $sum
			]);
		} else {
			\app\models\Persons::findOne($pid)->sendMail('ticketbuy', $theme, [
				'name' => $person->name,
				'second_name' => $person->second_name,
				'event' => $event,
				'buying_event' => $buying_event,
				'buying_seats' => $buying_seats,				
			]);
			return $return;
		}

	}




	public function actionMailer($uid) {
		\app\models\Persons::findOne($uid)->sendMail('ticketbuy', 'Пример письма', ['paramExample' => '123']);
	}



	// Frames
	public function actionDateframe($id)
	{
		$id = Yii::$app->request->get('id');
		$events = Events::find()->joinWith('seatings')->where(['event_id' => $id])->andwhere('DATE(date) >= DATE(NOW())')->orderBy(['date'=>SORT_ASC])->all();
		return $this->renderPartial('dateframe.twig', ['events' => $events]);
	}



	public function actionFormframe($id)
	{
		$event = Events::findOne($id);
		if ($event->date >= date("Y-m-d H:i:s")) {

			if ($event->seating_id) {
				$seatings = Seatings::find()->where(['id' => $event->seating_id])->one();
			} else {
				$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
				$seatings = Seatings::find()->where(['id' => $biblioevent->seating_id])->one();
			}

			$seats = Seats::find()->where(['seating_id' => $seatings->id])->all();

			return $this->renderPartial('formframe.twig', ['event' => $event, 'seats' => $seats]);

		} else {
			return $this->renderPartial('formframe.twig');
		}

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


	/* При покупке билетов проверяем есть ли у нас такой пользователь по mail */

	public function actionUserisset($email)
	{
		$mail = Yii::$app->request->get('email');
		$user = Persons::find()->where(['mail' => $mail])->one();

		return $this->renderPartial('userisset.twig', ['user' => $user]);
	}


	// Проверка покупки билета через яндекс
	public function actionTicketyandexbuy(){

		if(isset($_POST['id'])&&!empty($_POST['id'])) {
			$uid = $_POST['id'];
			$user = Persons::findOne($uid);
			
			$user->name = $_POST['name'];
			$user->second_name = $_POST['second_name'];
			$user->mail = $_POST['mail'];
			$user->phone = $_POST['phone'];

			$user->save();
		} else {
			$user = new Persons(); 
			
			$user->name = $_POST['name'];
			$user->second_name = $_POST['second_name'];
			$user->mail = $_POST['mail'];
			$user->phone = $_POST['phone'];

			$user->save();
			$uid = $user->id; 
		}

		

		$info = $_POST['info'];


		if(is_array($_POST['seat'])) {

			$sum = 0;
			$order_id = 't'.uniqid();

			foreach ($_POST['seat'] as $k => $v) {
				if($v > 0) {
					$ticket = new Tickets();
					$ticket->user_id = $uid;
					$ticket->order_id = $order_id;
					$ticket->event_id = $_POST['event_id'];
					$ticket->seat_id = $_POST['seat_id'][$k];
					$ticket->money = $_POST['money'][$k];
					$ticket->count = $v;
					$ticket->summa = $ticket->money * $ticket->count;
					$ticket->date = date("Y-m-d H:i:s");
					$ticket->info = $_POST['info'];
					if(isset($_POST['type'])){
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
			'user' => $user,
			'order_id' => $order_id,
			'info' => $info,
			'tickets' => $tickets,
			'sum' => $sum
		]);
	}

	public function actionTobewithus()
	{
		$model = new Contragent();

		$company = Companies::getCompany();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['tobewithusthanks']);
		}

		return $this->render('tobewithus.twig', ['model' => $model, 'company' => $company??Null]);
	}

	public function actionTobewithyou()
	{
		$model = new Contragent();
		$company = Companies::getCompany();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['tobewithusthanks']);
		}

		return $this->render('tobewithyou.twig', ['model' => $model, 'company' => $company??Null]);
	}

	public function actionTobewithusthanks()
	{
		$model = new Contragent();
		$company = Companies::getCompany();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['tobewithusthanks']);
		}

		return $this->render('tobewithusthanks.twig', ['model' => $model, 'company' => $company]);
	}


	public function actionDoc($id)
	{
		$model = EventFinance::findOne($id);

		$contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
		$contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();

        //	Vitalhit::pre($model);

		$event = Events::findOne($model->event_id);

    	//    if ($event->id == ''){  
    	//    	return $this->redirect('/login');
    	//		} 

		if($event) {
			$biblioevent = Biblioevents::find()->where(['id' => $event->event_id])->one();
			$place = Places::find()->where(['id' => $event->place_id])->one();
		}else{
			$biblioevent = Null;
			$place = Null;
		}

		$summa_p = Companies::Propis($model['summa']);
		$money = array(
			'summa_p' => $summa_p
		);

		$propis = Companies::Propis($model->summa);

		if ($model->contract_template_id == 10) {
			$content = $this->renderPartial('contract/2022foursiz-03.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);
		}
		elseif ($model->contract_template_id == 11){
			$content = $this->renderPartial('contract/2021foursiz-refund.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);
		}
		elseif ($model->contract_template_id == 12) {
			$content = $this->renderPartial('contract/2021foursiz-refund-new-name.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);
		}
		elseif ($model->contract_template_id == 13) {
			$content = $this->renderPartial('contract/2022foursiz-03-partner.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);
		}
		elseif ($model->contract_template_id == 14) {
			$content = $this->renderPartial('contract/2022-note-for-tax-01.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);
		}elseif ($model->contract_template_id == 4) {
			$content = $this->renderPartial('contract/2024make-concert.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place, 'propis' => $propis]);
		}
		else {
			$content = $this->renderPartial('contract/2022foursiz-03.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);
		}

		$mpdf = new \Mpdf\Mpdf(['tempDir' => Yii::$app->params['mpdf']]);
		$mpdf->WriteHTML($content);
		return $mpdf->Output();
	}



	public function actionPers()
	{
		$person = Persons::find()
		->where(['id' => 23689, 'mail' => "studiobaraban@gmail.com"])
		->one();

		echo "<pre>"; print_r($person); echo "</pre>"; die;
	}



	public function actionCopymail()
	{
		$users = User::find()->select(['id', 'username', 'email'])->where(['username' => null])->all();

		foreach ($users as $user) {
			$user->username = $user->email;
			$user->save();
		}
	}





}
