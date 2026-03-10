<?php

namespace app\Services\Ticket;
use app\models\Secretcode;

class SecretcodeService
{
    public static function discount($ticket=false, $secretcode=false)
    {

        $promocode  = Secretcode::find()->where(['secretcode.code' => $secretcode])->one();

        if(isset($promocode)){
            if ( isset($promocode->event_id) and $promocode->event_id != $ticket->event_id)
            {
            $ticket->promocode = $secretcode;   
            $ticket->admin =  "Секретное слово ".$secretcode." — не действует для этого события.";
            }else{
		    $ticket->promocode = $promocode->code;
		    $ticket->money = round($ticket->money/100*(100-$promocode->percent));
		    $ticket->summa = round($ticket->summa/100*(100-$promocode->percent));
		    $ticket->admin = "Вы использовали секретный код ".$promocode['code']. ". Ваша скидка: ".$promocode->percent.'%';
            }
    	}else {
            $ticket->promocode = $secretcode;   
            $ticket->admin = "";// "Секретный код ".$secretcode." — не активен.";
        }

        return $ticket;
    }
}
