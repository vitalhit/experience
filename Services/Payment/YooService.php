<?php

namespace app\Services\Payment;

use app\models\Payment;

class YooService
{

    // обычный платеж
    public static function pay($client, $name, $summa, $order_id, $customer, $items)
    {
        $response = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $summa,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://igoevent.com/yoo/success',
                ),
                'capture' => true,
                'description' => $name,
                'metadata' => array('order_id' => $order_id),
                'receipt' => array(
                    'customer' => $customer,
                    'items' => $items
                )
            ),
            uniqid('', true)
        );

        if (!empty($response)) {
            $payment = new Payment();
            $payment->order_id = $order_id;
            $payment->payment_id = $response->getId();
            $payment->create_at = $response->getCreatedAt()->format('Y-m-d H:i:s');
            $payment->status = 1;
            if (!$payment->save()) {
                // записать в лог
                // return json_encode($payment->getErrors(), JSON_UNESCAPED_UNICODE);
            }

            // получаем confirmationUrl для дальнейшего редиректа
            return $response->getConfirmation()->getConfirmationUrl();
        }

        return null;
    }


    // обычный платеж
    public static function normal($client, $summa, $orderId, $customer, $items)
    {
        $response = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $summa,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://igoevent.com/yoo/success',
                ),
                'capture' => true,
                'description' => 'Заказ ' . $orderId,
                'metadata' => array('orderId' => $orderId),
                'receipt' => array(
                    'customer' => $customer,
                    'items' => $items
                )
            ),
            uniqid('', true)
        );

        if (!empty($response)) {
            $iGoPayment = new Payment();
            $iGoPayment->order_id = $orderId;
            $iGoPayment->payment_id = $response->getId();
            $iGoPayment->create_at = $response->getCreatedAt()->format('Y-m-d H:i:s');
            $iGoPayment->status = 1;
            if (!$iGoPayment->save()) {
                // записать в лог
                // return json_encode($iGoPayment->getErrors(), JSON_UNESCAPED_UNICODE);
            }

            // получаем confirmationUrl для дальнейшего редиректа
            return $response->getConfirmation()->getConfirmationUrl();
        }

        return null;
    }


    // платеж для рекуррентных списаний
    public static function recurrent($client, $summa, $orderId, $customer, $items)
    {
        $response = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $summa,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://igoevent.com/yoo/success',
                ),
                'payment_method_data' => array('type' => 'bank_card'),
                'save_payment_method' => true,
                'capture' => true,
                'description' => 'Заказ ' . $orderId,
                'metadata' => array('orderId' => $orderId),
                'receipt' => array(
                    'customer' => $customer,
                    'items' => $items
                )
            ),
            uniqid('', true)
        );

        if (!empty($response)) {
            $iGoPayment = new Payment();
            $iGoPayment->order_id = $orderId;
            $iGoPayment->payment_id = $response->getId();
            $iGoPayment->payment_method_id = $response->getPaymentMethod()->getId();
            if ($response->getPaymentMethod()->getSaved()) {
                $iGoPayment->saved = 1;
            } else {
                $iGoPayment->saved = 0;
            }
            $iGoPayment->create_at = $response->getCreatedAt()->format('Y-m-d H:i:s');
            $iGoPayment->status = 1;
            if (!$iGoPayment->save()) {
                // записать в лог
                // return json_encode($iGoPayment->getErrors(), JSON_UNESCAPED_UNICODE);
            }

            // получаем confirmationUrl для дальнейшего редиректа
            return $response->getConfirmation()->getConfirmationUrl();
        }

        return null;
    }
}
