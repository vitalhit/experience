<?php

namespace app\controllers;

use Yii;
use app\models\LogCron;
use app\models\Messages;
use app\models\Tickets;
use app\models\Rents;
use app\models\Seats;
use app\models\Biblioevents;
use app\models\Events;
use app\models\Persons;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * MessagesController implements the CRUD actions for Messages model.
 */
class MessagesController extends Controller
{

	public function actionIndex()
	{

		// Находим все сообщения
		$messages = Messages::find()->all();

		return $this->render('index.twig', [
			'messages' => $messages,
		]);
	}


	// Отправка билета на почту
	public function actionSendticket()
	{
		//		file_put_contents('test.txt', PHP_EOL.Date('Запустился Sendticket in MessagesController '), FILE_APPEND);
		
		$alltickets = Tickets::find()->where(['tickets.send' => 0])
		->andWhere(['or', ['tickets.status' => 5], ['and', 'tickets.status = 1', 'tickets.summa = 0']])
		->joinWith('seats')->groupBy('order_id')->limit(5)->all();

		// echo "<pre>";
		// print_r($alltickets);
		// echo "</pre>";
		
		$i = 0;
		$result = null;
		foreach($alltickets as $ticket) {

			// file_put_contents('test.txt', PHP_EOL . 'ticketId: ' . json_encode($ticket->id), FILE_APPEND);

			// Найдем все билеты из этого заказа - чтобы отправить их в одном письме
			$orders = Tickets::find()->where(['order_id' => $ticket->order_id])->andWhere(['or', ['tickets.status' => 5], ['and', 'tickets.status = 1', 'tickets.summa = 0']])->all();
			foreach($orders as $order) {
				// чтобы не прерывалась отправка при некорректном mail
				$order->send = 3;
				$order->save();
			}
				// // Проверим, что еще не отправляли
				// $message = Messages::find()->where(['order_id' => $ticket->order_id])->andWhere(['type' => 2])->one();
				// if (empty($message)) {
				// }
			Persons::ticketMail($orders);
			$i++;
			$result[] = $ticket->id;
		}

		// Если билеты не отправились раннее то ставим их обратно в очередь.
		$dontsendtickets =  Tickets::find()->where(['tickets.send' => 3])->all();
		foreach($dontsendtickets as $ticket) {
			$ticket->send = 0;
			$ticket->save();
		}



		echo "cработало, количество бы выводить.. ";

		// Если запустил не человек!
		if (empty(Yii::$app->user->id)) {
			LogCron::setLog('sendticket',json_encode($result,JSON_UNESCAPED_UNICODE), 1);
		}
	}



	// Напоминание о билете
	public function actionRemember()
	{
		$yesterday = date("Y-m-d",strtotime("-1 day"));

		// Находим все неоплаченные билеты за вчера, у которых сумма > 0, группируем по Гостю и событию. Потому что гость может выписать много билетов на одно событие (с разными order_id)
		$tickets = Tickets::find()
		->joinWith('persons')
		->joinWith('events')
		->where(['DATE(tickets.date)' => $yesterday])
		->andWhere('tickets.status = 1')
		->andWhere('tickets.summa > 0')
		->groupBy('tickets.user_id, tickets.event_id')
		->all();

		foreach($tickets as $ticket) {
			// Если уже есть оплаченный любой билет этого гостя на это событие - то НЕ напоминаем!
			$payed = Tickets::find()
			->where(['user_id' => $ticket->user_id])
			->andWhere(['tickets.event_id' => $ticket->event_id])
			->andWhere('tickets.status = 5')
			->one();
			if (empty($payed)) {
				// Если еще не отправляли напоминание - то отправляем
				$message = Messages::find()->where(['order_id' => $ticket->order_id])->andWhere(['type' => 1])->one();
				if (empty($message)) {
					$all[] = $ticket;
					$person = Persons::findOne($ticket->user_id);
					$message = Messages::saveRememberMail($person, $ticket->order_id, 'билет');

					$person->sendMail('rememberticketbuy', 'Покупка билета!', [
						'name' => $person->name,
						'second_name' => $person->second_name,
						'ticket' => $ticket,
						'message' => $message
					]);
					sleep(3);
				}
			}
		}
		// echo "<pre>";
		// print_r($all);
		// echo "</pre>";

		return $this->render('remember.twig', ['all' => $all]);
	}



	public function actionRememberrent()
	{
		$yesterday = date("Y-m-d",strtotime("-1 day"));

		// Находим все неоплаченные аренды за вчера
		$rents = Rents::find()
		->where(['DATE(rents.create_at)' => $yesterday])
		->andWhere('rents.status = 1')
		->groupBy('rents.person_id')
		->all();


		foreach($rents as $rent) {
			// Если уже есть оплаченная аренда этого гостя в этот день - то НЕ напоминаем!
			$payed = Rents::find()
			->where(['rents.person_id' => $rent->person_id])
			->andWhere(['DATE(rents.create_at)' => $yesterday])
			->andWhere('rents.status = 5')
			->one();
			if (empty($payed)) {
				// Если еще не отправляли напоминание - то отправляем
				$message = Messages::find()->where(['order_id' => $rent->order_id])->andWhere(['type' => 1])->one();
				if (empty($message)) {
					$all[] = $rent;
					$person = Persons::findOne($rent->person_id);
					$message = Messages::saveRememberMail($person, $rent->order_id, 'аренда');

					$person->sendMail('rememberrentbuy', 'Бронь аренды!', [
						'name' => $person->name,
						'second_name' => $person->second_name,
						'rent' => $rent,
						'message' => $message
					]);
					sleep(3);
				}
			}
		}
		// echo "<pre>";
		// print_r($rents);
		// echo "</pre>";

		return $this->render('remember.twig', ['all' => $all]);
	}
}
