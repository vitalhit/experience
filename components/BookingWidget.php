<?php

namespace app\components;
use app\models\Bookingapi;
use app\models\Companies;

class BookingWidget
{
    // Уведомление в меню - сколько новых заявок
    public static function count(){
        $booking = Bookingapi::find()->where(['status_id' => 1])->andWhere(['company_id' => Companies::getCompanyId()])->count();
        return $booking;
    }
}