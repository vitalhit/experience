<?php

namespace app\controllers;

use app\Repository\TicketRepository;
use yii\web\Controller;
use YooKassa\Client;

// переименовали в Yoo - этот можно удалить
class AvisourlController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionPayment()
    {
        // Прошлый магазин Хорошя республика
        // $shopId = '157496';
        // $shopPassword = '8yJjbkp33xXDo587buw9';
        // $orderNumber = $_POST['orderNumber'];

        if (empty($_POST['orderNumber']) && (int)$_POST['orderNumber'] < 1) {
            return null;
        }

        // создали яндекс клиент
        $client = new Client();
        $client->setAuth('791346', 'live_CFXDZp9L2kaWwxVsCM-l_IKOuBFPEgvm25G-9tOImAY');

        // нашли билеты по номер заказа
        $ticketR = new TicketRepository();
        $tickets = $ticketR->byOrderId($_POST['orderNumber']);

        $summa = 0;
        foreach ($tickets as $ticket) {
            $summa += $ticket->summa;
        }

        $summa = sprintf("%.2f", $summa);

        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $summa,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://igoevent.com/avisourl/success',
                ),
                'capture' => true,
                'description' => 'Заказ ' . $_POST['orderNumber'],
            ),
            uniqid('', true)
        );

        return $payment;
    }


    // страница спасибо
    public function actionSuccess()
    {
        return $this->render('success.twig');
    }

}
