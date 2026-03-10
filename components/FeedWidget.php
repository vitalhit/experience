<?php

namespace app\components;
use app\models\Feedback;

class FeedWidget
{
    // Уведомление в меню - сколько тасков на проверку
    public static function count(){
        return Feedback::find()->where(['feedback.status' => 1])->count();
    }
}