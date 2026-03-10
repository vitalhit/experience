<?php

namespace app\controllers;

use Yii;
use app\models\Ads;
use app\models\Band;
use app\models\BandEvent;
use app\models\Biblioevents;
use app\models\BiblioeventSection;
use app\models\BiblioeventBand;
use app\models\Bookingapi;
use app\models\Cities;
use app\models\Companies;
use app\models\Categoryevents;
use app\models\Contragent;
use app\models\Events;
use app\models\EventFinance;
use app\models\Img;
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
use app\models\Post;
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
use app\models\Vitalhit;
use Da\QrCode\QrCode;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

//use yii\data\ActiveDataProvider;
//use yii\web\Controller;
//use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;



class DocController extends Controller
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


	public function actionFoursiz($id)
	{
		$model = EventFinance::findOne($id);

		$contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
		$contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();

        //Vitalhit::pre($model);

		$event = Events::findOne($model->event_id);

     //    if ($event->id == ''){  
     //    return $this->redirect('/login');
    	// } 

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

        // echo "<pre>";
        // print_r($place);              
        // echo "</pre>";

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
		}
		else {
			$content = $this->renderPartial('contract/2022foursiz-03.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);
		}

		$mpdf = new \Mpdf\Mpdf(['tempDir' => Yii::$app->params['mpdf']]);
        $mpdf->WriteHTML($content);
        return $mpdf->Output();

	}

	public function actionPromoevent($id)
	{
		$model = EventFinance::findOne($id);

		$contragent1 = Contragent::find()->where(['id' => $model['from_contragent']])->one();
		$contragent2 = Contragent::find()->where(['id' => $model['to_contragent']])->one();

        //Vitalhit::pre($model);

		$event = Events::findOne($model->event_id);

     //    if ($event->id == ''){  
     //    return $this->redirect('/login');
    	// } 

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

        // echo "<pre>";
        // print_r($place);              
        // echo "</pre>";

		
		$content = $this->renderPartial('contract/2022foursiz-promoevent.twig', ['model' => $model, 'money' => $money, 'contragent1' => $contragent1, 'contragent2' => $contragent2, 'event' => $event, 'biblioevent'=> $biblioevent, 'place'=>$place]);

		$mpdf = new \Mpdf\Mpdf(['tempDir' => Yii::$app->params['mpdf']]);
        $mpdf->WriteHTML($content);
        return $mpdf->Output();

	}


}
