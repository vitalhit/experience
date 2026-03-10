<?php

namespace app\controllers;

use Yii;
use app\models\Vk;
use app\models\Events;
use app\models\LogPay;
use app\models\Rents;
use app\models\Tickets;
use yii\web\Controller;

/**
 * PayController implements the CRUD actions for Pay model.
 */
class PayController extends Controller
{


    public $enableCsrfValidation = false;

    public function actionCheckurl()
    {
        // file_put_contents('/home/v/vitalhit/newcrm/public_html/web/uploads/checkpost.txt',json_encode($_POST));

        $shopId = '157496';
        $shopPassword = '8yJjbkp33xXDo587buw9';
        $orderNumber = $_POST['orderNumber'];

        $tickets = Tickets::find()->where(['order_id' => $orderNumber])->all();
        if (empty($tickets)) { // Если билета нет - ищем в арендах
            $tickets = Rents::find()->where(['order_id' => $orderNumber])->all();
        }
        $summa = 0;
        foreach ($tickets as $ticket) {
            $summa = $summa + $ticket->summa;
        }
        $summa = sprintf("%.2f", $summa);

        $hash = strtoupper(MD5($_POST['action'] . ';' . $summa . ';' . $_POST['orderSumCurrencyPaycash'] . ';' . $_POST['orderSumBankPaycash'] . ';' . $shopId . ';' . $_POST['invoiceId'] . ';' . $_POST['customerNumber'] . ';' . $shopPassword));

        // var_dump(file_put_contents('/home/v/vitalhit/newcrm/public_html/web/uploads/checktest.txt', PHP_EOL.Date('d.m.Y H:i:s - ').json_encode($_POST).PHP_EOL.' hash - '.$hash.PHP_EOL.' summa - '.$summa, FILE_APPEND));

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = json_encode($_SERVER['REMOTE_ADDR']);
        } else {
            $ip = null;
        }

        if ($hash != $_POST['md5']) {
            $code = 1;
            foreach ($tickets as $ticket) {
                $ticket->status = 2; // запрос на оплату не отправлен
                if ($ticket->save()) {
                    LogPay::setLog($ticket->order_id, 'Билет НЕ одобрен', $ip, 2);
                }
            }
        } else {
            $code = 0;
            foreach ($tickets as $ticket) {
                $ticket->status = 3; // запрос на отплату прошел!
                if ($ticket->save()) {
                    LogPay::setLog($ticket->order_id, 'Билет одобрен', $ip, 1);
                }
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?> <checkOrderResponse performedDatetime="' . $_POST['requestDatetime'] . '" code="' . $code . '"' . ' invoiceId="' . $_POST['invoiceId'] . '" shopId="' . $shopId . '"/>';
        // file_put_contents('/home/v/vitalhit/newcrm/public_html/web/uploads/CHECKURL.txt',PHP_EOL.Date('d.m.Y H:i:s - ').$xml, FILE_APPEND);
        return $xml;
    }


    public function actionAvisourl()
    {
        // file_put_contents('test.txt', PHP_EOL . 'АВИЗО: ' . json_encode($_POST), FILE_APPEND);

        $ip = null;
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = json_encode($_SERVER['REMOTE_ADDR']);
        }

        $shopId = '157496';
        $shopPassword = '8yJjbkp33xXDo587buw9';
        $orderNumber = $_POST['orderNumber'];

        $tickets = Tickets::find()->where(['order_id' => $orderNumber])->all();
        if (empty($tickets)) { // Если билета нет - ищем в арендах
            $tickets = Rents::find()->where(['order_id' => $orderNumber])->all();
        }

        $event = Events::find()->joinWith('biblioevents')->where(['events.id' => $tickets[0]->event_id])->one();
        $summa = 0;
        foreach ($tickets as $ticket) {
            $summa = $summa + $ticket->summa;
        }
        $summa = sprintf("%.2f", $summa);


        $hash = strtoupper(MD5($_POST['action'] . ';' . $summa . ';' . $_POST['orderSumCurrencyPaycash'] . ';' . $_POST['orderSumBankPaycash'] . ';' . $shopId . ';' . $_POST['invoiceId'] . ';' . $_POST['customerNumber'] . ';' . $shopPassword));

        if ($hash != $_POST['md5']) { // Если хеш не совпадает
            $code = 1;
            foreach ($tickets as $ticket) {
                $ticket->status = 4; // Билет не оплачен
                if ($ticket->save()) {
                    LogPay::setLog($ticket->order_id, 'Билет НЕ оплачен', $ip, 2);
                }
            }
        } else {
            $code = 0;
            foreach ($tickets as $ticket) {
                file_put_contents('test.txt', PHP_EOL .' айди - ' . $ticket->id.' статус - ' . $ticket->status, FILE_APPEND);

                $ticket->status = 5; // Если билет оплачен и деньги получены
                if ($ticket->save()) {
                    if ($event->biblioevents->vksend) {
                        Vk::Send('ОПЛАТА билета на событие "' . $event->biblioevents->name . '"', [trim($event->biblioevents->vksend)]);
                    } else {

                        Vk::Send('Виталий это работает!  "' . $event->biblioevents->name . '"', ['90794']);
                    }
                    // LogPay::setLog($ticket->order_id, 'Билет оплачен', $ip, 1);
                }
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?><paymentAvisoResponse performedDatetime="' . $_POST['requestDatetime'] . '" code="' . $code . '" invoiceId="' . $_POST['invoiceId'] . '" shopId="' . $shopId . '"/>';

        // file_put_contents('test.txt', PHP_EOL . Date('d.m.Y H:i:s - ') . $xml, FILE_APPEND);

        return $xml;
    }


    public function actionSuccess()
    {
        $get = Yii::$app->request->get();
        $id = $get['orderNumber'];
        return $this->redirect(['/profile']);
    }


    public function actionFail()
    {
        $get = Yii::$app->request->get();
        $ticket = Tickets::find()->where(['order_id' => $get['orderNumber']])->one();
        $event = Events::find()->where(['id' => $ticket->event_id])->with('biblioevents')->one();
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = json_encode($_SERVER['REMOTE_ADDR']);
        } else {
            $ip = null;
        }
        LogPay::setLog($ticket->order_id, 'Техническая ошибка', $ip, 2);
        return $this->render('fail.twig', ['get' => $get, 'event' => $event]);
    }


    public function actionError()
    {
        if (isset($_POST['orderNumber'])) {
            $orderNumber = $_POST['orderNumber'];
            $tickets = Tickets::find()->where(['order_id' => $orderNumber])->all();
            print_r($tickets);
        }
        return $this->render('error.twig');
    }


}
