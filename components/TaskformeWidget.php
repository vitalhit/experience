<?php

namespace app\components;
use Yii;
use app\models\Tasks;

class TaskformeWidget
{
    // Уведомление в меню - сколько у меня новых тасков
    public static function count(){

        $id = Yii::$app->user->id;
        $tasks = Tasks::find()->where(['owner_id' => $id])->andwhere(['status_id' => 1])->count();

        return $tasks;
    }
}