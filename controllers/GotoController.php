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



class GotoController extends Controller
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


	public function actionIndex ( $event_id= false)
	{
		
		if ( is_numeric($event_id))
		{
			$event =  Events::find()->where(['id' => $event_id])->one();

		// echo "<pre>";
		// print_r($event);
		// echo "</pre>";die;

		return $this->redirect('https://igoevent.com/'.$event['alias']);

		}else {
			return;
		}

		
	}





}
