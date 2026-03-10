<?php

namespace app\modules\crm\controllers;

use Yii;
use YooKassa\Client;
use app\models\AuthAssignment;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

//live_tz00RUGRdtKXpGDPb1Da2zzFFgD21w70Rh_T_ODfaNg

class YooController extends Controller
{

    public $enableCsrfValidation = false;

    public function actionMy()
    {
        // Реальный!!!
//        $shopId = 791346;
//        $token = 'live_tz00RUGRdtKXpGDPb1Da2zzFFgD21w70Rh_T_ODfaNg';

        // Тестовый!!!
        $shopId = 551228;
        $token = 'test_i-5_scRxq9TkkPm3kyVkwwUbTBYkDPgU49EcKLY7sVM';




        $client = new Client();
        $client->setAuth($shopId, $token);




        $idempotenceKey = uniqid('', true);
        $response = $client->createPayment(
            array(
                'amount' => array(
                    'value' => '12.00',
                    'currency' => 'RUB',
                ),
                'payment_method_data' => array(
                    'type' => 'bank_card',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://www.merchant-website.com/return_url',
                ),
                'description' => 'Заказ №72',
            ),
            $idempotenceKey
        );

        //get confirmation url
        $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();




        echo "<pre>"; print_r($confirmationUrl); echo "</pre>";
    }


}
