<?php

namespace app\controllers;

use Yii;

use app\Services\Payment\YooService;
use app\Services\Ticket\TicketService;

use app\Repository\GuestRepository;
use app\Repository\TicketRepository;

use YooKassa\Client;

use yii\web\Controller;
use YooKassa\Model\Notification\NotificationFactory;
use YooKassa\Model\NotificationEventType;

class YooController extends Controller
{
    public $enableCsrfValidation = false;


    public function actionPayment()
    {
        $post = Yii::$app->request->post();
//        $post['order_id'] = 't6564222384439';
//        $post['user_id'] = 1;

//        file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('H:i:s') . '------  PAYMENT ------ ', FILE_APPEND);
//        file_put_contents('test.txt', PHP_EOL . json_encode($post), FILE_APPEND);

        if (empty($post['order_id']) && empty($post['user_id'])) return 'Недостаточно данных';

        // создали яндекс клиент
        $client = new Client();
//        $client->setAuth('897273', 'test_EVdICXDp9QVHejDzGXJB8V_lDcU7pbfX-M6DJXYHdRw');
        $client->setAuth('791346', 'live_CFXDZp9L2kaWwxVsCM-l_IKOuBFPEgvm25G-9tOImAY');

        // клиент преобразованный в формат яндекс кассы
        $guestR = new GuestRepository();
        $customer = $guestR->customerByPersonId($post['user_id']);

        // билеты по номеру заказа
        $ticketR = new TicketRepository();
        $tickets = $ticketR->byOrderId($post['order_id']);

        // сумма всех билетов
        $summa = TicketService::summa($tickets);

        // билеты преобразованные в формат яндекс кассы
        $items = $ticketR->ticketsForYoo($tickets);

        // создадим обычный платеж
        $name = 'Участие в мероприятии orderId ' . $post['order_id'];
        $response = YooService::pay($client, $name, $summa, $post['order_id'], $customer, $items);
//        $response = YooService::normal($client, $summa, $post['order_id'], $customer, $items);


        if (!empty($response) ) {
            Yii::$app->response->redirect(stripcslashes($response));
            Yii::$app->end();
        }

        return 'ok';
    }


    // страница спасибо
    public function actionSuccess()
    {
        return $this->render('success.twig');
    }


    // уведомления
    public function actionNotification()
    {
        $source = file_get_contents('php://input');
        $data = json_decode($source, true);

        //file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('H:i:s') . '------  notification ------ ', FILE_APPEND);
        //file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('H:i:s') . 'data:' . json_encode($data), FILE_APPEND);

        $factory = new NotificationFactory();
        $notificationObject = $factory->factory($data);

        // создали яндекс клиент
        $client = new Client();


        if (!$client->isNotificationIPTrusted($_SERVER['REMOTE_ADDR'])) {
            header('HTTP/1.1 400 Something went wrong');
            exit();
        }

        $yooDTO = array(
            'paymentId' => $notificationObject->getObject()->getId(),
            'paymentStatus' => $notificationObject->getObject()->getStatus(),
            'order_id' => $notificationObject->getObject()->getMetadata()['order_id'],
        );

        //file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('H:i:s ') . 'response yooDTO:' . json_encode($yooDTO), FILE_APPEND);

        // билеты по номеру заказа
        $ticketR = new TicketRepository();
        $tickets = $ticketR->byOrderId($yooDTO['order_id']);

        if ($notificationObject->getEvent() === NotificationEventType::PAYMENT_SUCCEEDED) {
            // Билет оплачен
            foreach ($tickets as $ticket) {
                $ticket->status = 5;
                $ticket->save();
            }
        } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE) {
            // Специфичная логика
            // ...
        } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_CANCELED) {
            // Специфичная логика
            // ...
        } elseif ($notificationObject->getEvent() === NotificationEventType::REFUND_SUCCEEDED) {
            $yooDTO = array(
                'refundId' => $notificationObject->getObject()->getId(),
                'refundStatus' => $notificationObject->getObject()->getStatus(),
                'order_id' => $notificationObject->getObject()->getPaymentId(),
            );
            // Специфичная логика
        } else {
            header('HTTP/1.1 400 Something went wrong');
            exit();
        }
        // $client->setAuth('791346', 'live_CFXDZp9L2kaWwxVsCM-l_IKOuBFPEgvm25G-9tOImAY');

        // // Получим актуальную информацию о платеже
        // if ($paymentInfo = $client->getPaymentInfo($yooDTO['order_id'])) {
        //     $paymentStatus = $paymentInfo->getStatus();
        //     // ...
        // } else {
        //     header('HTTP/1.1 400 Something went wrong');
        //     exit();
        // }

        return 'ok';
    }


}
