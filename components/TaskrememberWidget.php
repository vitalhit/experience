<?php

namespace app\components;
use Yii;
use app\models\Tasks;

class TaskrememberWidget
{
    // Уведомление в меню - напоминание о тасках
    public static function count(){

        $id = Yii::$app->user->id;
        $thisdate = date('Y-m-d H:i:s');
        $tasks = Tasks::find()->where(['owner_id' => $id, 'status_id' => 8])->andwhere('start < :a', ['a' => $thisdate])->count();

        return $tasks;
    }
}