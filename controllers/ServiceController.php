<?php

namespace app\controllers;

use Yii;
use app\models\Biblioevents;
use app\models\Tickets;
use yii\web\Controller;
use yii\web\Response;

class ServiceController extends Controller
{
    // СЕРВИСНЫЙ КОНТРОЛЛЕР для технических задач
    // ------------------------------------------------------------------------------

    // добавить company_id в ticket
    public function actionCompany()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $tickets = Tickets::find()->where(['tickets.company_id' => null])->joinWith('biblioevent')->limit(5000)->all();

        $i = 0;
        foreach ($tickets as $ticket) {
            if (!empty($ticket->biblioevent) && !empty($ticket->biblioevent->company_id)) {
                $ticket->biblioevent_id = $ticket->biblioevent->id;
                $ticket->company_id = $ticket->biblioevent->company_id;
                if ($ticket->save()) {
                    $i++;
                }
            }
        }

        return 'Добавил company_id в ' . $i . ' билетов!';
    }


    // добавить name, secondname, email, phone и user_id в ticket
    public function actionPerson()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $tickets = Tickets::find()->where(['tickets.name' => null])->joinWith('persons')->limit(5000)->all();

        $i = 0;
        foreach ($tickets as $ticket) {
            if (!empty($ticket->persons)) {
                $ticket->user = $ticket->persons->user_id;
                $ticket->name = $ticket->persons->name;
                $ticket->secondname = $ticket->persons->second_name;
                $ticket->email = $ticket->persons->mail;
                $ticket->phone = $ticket->persons->phone;
                if ($ticket->save()) {
                    $i++;
                }
            }
        }

        return 'Добавил имена в ' . $i . ' билетов!';
    }


}
