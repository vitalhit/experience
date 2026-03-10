<?php

namespace app\controllers;

use Yii;
use app\models\Events;
use app\models\LogPay;
use app\models\Biblioevents;
use app\models\Companies;
use app\models\Bookingapi;
use app\models\BookList;
use app\models\Categoryevents;
use app\models\Seats;
use app\models\Seatings;
use app\models\Tickets;
use app\models\Places;
use app\models\Persons;
use app\models\Users;
use app\models\Rooms;
use app\models\Rents;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm as Login;
use app\models\Signup;
use app\models\PasswordResetRequest;
use app\models\ResetPassword;
use app\models\ContactForm;
use Da\QrCode\QrCode;
use YandexCheckout\Client;

class MoneyController extends Controller
{

	public $enableCsrfValidation = false;

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
		];
	}



	// Яндекс Касса API
	// ------------------------------------------------------------------------------

	public function actionPay($order_id)
	{
		$tickets = Tickets::find()->where(['order_id' => $order_id])->all();

		$sum = 0;
		foreach ($tickets as $ticket) {
			$sum = $sum + $ticket->summa;
		}
		$sum = sprintf("%.2f", $sum);


		if (!empty($tickets)) {

			$client = new Client();
			$client->setAuth('551228', 'test_i-5_scRxq9TkkPm3kyVkwwUbTBYkDPgU49EcKLY7sVM');

			$payment = $client->createPayment(
				array(
					'amount' => array(
						'value' => $sum,
						'currency' => 'RUB',
					),
					'confirmation' => array(
						'type' => 'redirect',
						'return_url' => 'https://igoevent.com/money/index',
					),
					'description' => $order_id,
					'capture' => true,
				),
				uniqid('', true)
			);


			if (!empty($payment)) {
				foreach ($tickets as $ticket) {
					$ticket->status = 3; // запрос на отплату прошел!
					if ($ticket->save()) {
						$logpay = LogPay::setLog($ticket->order_id, 'Ya.API Билет одобрен', 1);
					}
				}			
			} else {
				foreach ($tickets as $ticket) {
					$ticket->status = 2; // запрос на оплату не отправлен
					if ($ticket->save()) {
						$logpay = LogPay::setLog($ticket->order_id, 'Ya.API Билет НЕ одобрен', 2);
					}
				}
			}


			// echo "<pre>";
			// print_r($client);
			// echo "</pre>";

			file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - сум').json_encode($sum, JSON_UNESCAPED_UNICODE), FILE_APPEND);

			file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - мани пэй').json_encode($payment, JSON_UNESCAPED_UNICODE), FILE_APPEND);


			$url = $payment->confirmation->confirmation_url;

			return $this->redirect($url);
		}
	}



	public function actionSuccess()
	{
		file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - спасибо ').json_encode(json_encode($_POST), JSON_UNESCAPED_UNICODE), FILE_APPEND);

		echo "Спасибо! Успех!";
	}












	public function actionNoti()
	{

		// file_put_contents('test.txt',var_export($_POST,true).PHP_EOL, FILE_APPEND);
		file_put_contents ('test.txt', print_r(json_decode(file_get_contents("php://input")), true));

		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти пост ') . json_encode($_POST), FILE_APPEND);

		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти пост ') . json_encode($_POST), FILE_APPEND);

		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти пре ') . "<pre>" . print_r($_POST) . "</pre>", FILE_APPEND);

		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти пре эн ') . "<pre>" . print_r(json_encode($_POST)) . "</pre>", FILE_APPEND);

		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти пре де') . "<pre>" . print_r(json_decode(json_encode($_POST), true)) . "</pre>", FILE_APPEND);



		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти пост энк').var_dump(json_decode(json_encode($_POST), true)), FILE_APPEND);

		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти пост айди').var_dump(json_encode($_POST['id'])), FILE_APPEND);

		// $notification = json_encode($_POST);

		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти ').json_encode($notification->id), FILE_APPEND);
		


		// $post = json_decode(json_encode($_POST));

		// echo "<pre>";
		// print_r($post);
		// echo "</pre>";


		// $payment = $notification->getObject();
		// file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('d.m.Y H:i:s - ноти айди ').json_encode($payment->id), FILE_APPEND);


		// $client->capturePayment(
		// 	array(
		// 		'amount' => $payment->amount,
		// 	),
		// 	$payment->id,
		// 	uniqid('', true)
		// );

		return $this->render('noti.twig', ['notification' => $notification ?? null]);
	}


}
