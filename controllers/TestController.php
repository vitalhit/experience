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



class TestController extends Controller
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

	

	public function actionTestEmail()
	
	{
	    try {
	        $result = Yii::$app->mailer->compose()
	            ->setTo('v@igoevent.com')
	            ->setSubject('Test Email')
	            ->setTextBody('Test message')
	            ->send();

	        if ($result) {
	            echo "Email sent successfully to {$email}\n";
	        } else {
	            echo "Failed to send email\n";
	        }
	    } catch (\Exception $e) {
	        echo "Error: " . $e->getMessage() . "\n";
	    }
	}

	public function actionDiagnoseEmail()
	{
	    $hosts = [
	        'smtp.yandex.ru:587',
	        'smtp.yandex.ru:465', 
	        'smtp.yandex.com:587',
	        'smtp.yandex.com:465'
	    ];
	    
	    foreach ($hosts as $host) {
	        list($hostname, $port) = explode(':', $host);
	        
	        try {
	            $transport = new \Swift_SmtpTransport($hostname, $port, $port == 465 ? 'ssl' : 'tls');
	            $transport->setTimeout(10);
	            $mailer = new \Swift_Mailer($transport);
	            
	            $transport->start();
	            echo "✓ Успешно: {$host}<br>";
	            $transport->stop();
	            
	        } catch (\Exception $e) {
	            echo "✗ Ошибка: {$host} - " . $e->getMessage() . "<br>";
	        }
	    }
	}

	public function actionTesttest()
	{

		echo Yii::$app->params['supportEmail'];

		$mail = \Yii::$app->mailer->compose()
            ->setTo(['v@igoevent.com'])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Сервис igoevent.com'])
            ->setSubject('Тестовое письмо!')
            ->setHtmlBody('text')
            ->send();
	}

}
