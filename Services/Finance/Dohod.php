<?php

namespace app\Services\Finance;

use Yii;

use app\models\Biblioevents;
use app\models\Events;

use yii\helpers\ArrayHelper;

class Dohod
{
    public static function all($company): array
    {
        $biblioevents = Biblioevents::find()->select(['id', 'name'])->where(['company_id' => $company->id])->asArray()->all();
        $bib_ids = ArrayHelper::getColumn($biblioevents, 'id');
        $events = Events::find()->select(['id', 'event_id'])->where(['event_id' => $bib_ids])->asArray()->all();
        $events_ids = implode(",", ArrayHelper::getColumn($events, 'id'));
        if (empty($events_ids)) {
            return [];
        }
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT `biblioevents`.`id`, `biblioevents`.`name`, `tickets`.`event_id`, SUM(`tickets`.`summa`) as summa, COUNT(`tickets`.`summa`) as count, DATE_FORMAT(`tickets`.`date`, '%Y-%m') as period 
            FROM `tickets`
            LEFT JOIN `events` ON `events`.`id` = `tickets`.`event_id` 
            LEFT JOIN `biblioevents` ON `biblioevents`.`id` = `events`.`event_id` 
            WHERE `tickets`.`status` = 5 AND `tickets`.`event_id` IN ($events_ids) 
            GROUP BY `tickets`.`event_id`, period 
            ORDER BY period");
        $result = $command->queryAll();

        $start = '2018-01';
        $itog = [];
        while ($start <= date('Y-m')) {
            $arr = [];
            foreach ($result as $item) {
                if ($item['period'] == $start) {
                    $arr[] = $item;
                }
            }
            if (!empty($arr)) {
                $itog[] = array('date' => $start, 'dohod' => $arr);
            }
            $start = date('Y-m', strtotime("$start +1 month"));
        }
        return $itog;
    }
}
