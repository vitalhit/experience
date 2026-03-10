<?php

namespace app\components;
use Yii;
use app\models\Tasks;

class TaskforcheckWidget
{
    // Уведомление в меню - сколько тасков на проверку
    public static function count(){

        $id = Yii::$app->user->id;
        $tasks = Tasks::find()->where(['creator_id' => $id])->andwhere(['status_id' => 5])->count();

        return $tasks;
    }
}