<?php

namespace app\controllers;

use Yii;
use app\models\Tickets;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CheckurlController
 */
class CheckurlController extends Controller
{
    
    public $enableCsrfValidation = false;

	public function actionIndex()
	{
        
        //file_put_contents('/home/v/vitalhit/crm.goodrepublic.ru/public_html/web/uploads/checkpost.txt',json_encode($_POST));

        $shopId = '157496';
        $shopPassword = '8yJjbkp33xXDo587buw9';
        $orderNumber = $_POST['orderNumber'];

        $tickets = Tickets::find()->where(['order_id' => $orderNumber])->all();
        $summa = 0;
        foreach ($tickets as $ticket) {
            $summa = $summa + $ticket->summa;
            $summa = sprintf("%.2f", $summa);
        }

        $hash = strtoupper(MD5($_POST['action'].';'.$summa.';'.$_POST['orderSumCurrencyPaycash'].';'.$_POST['orderSumBankPaycash'].';'.$shopId.';'.$_POST['invoiceId'].';'.$_POST['customerNumber'].';'.$shopPassword));

        // var_dump(file_put_contents('/home/v/vitalhit/crm.goodrepublic.ru/public_html/web/uploads/checktest.txt', PHP_EOL.Date('d.m.Y H:i:s - ').json_encode($_POST).PHP_EOL.' hash - '.$hash.PHP_EOL.' summa - '.$summa, FILE_APPEND));


        if ($hash != $_POST['md5']){
            $code = 1;
            foreach ($tickets as $ticket) {
                $ticket->status = 1;
                $ticket->save();
            }
        }else{
            $code = 0;
            foreach ($tickets as $ticket) {
                $ticket->status = 11;
                $ticket->save();
            }
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<checkOrderResponse performedDatetime="'. $_POST['requestDatetime'] .'" code="'.$code.'"'. ' invoiceId="'. $_POST['invoiceId'] .'" shopId="'. $shopId .'"/>';
        file_put_contents('/home/v/vitalhit/crm.goodrepublic.ru/public_html/web/uploads/check_result.txt',$code);
    }

}
