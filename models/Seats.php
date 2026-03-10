<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "seats".
 *
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property int $count
 * @property int $price
 * @property string $sec
 * @property string $date_start
 * @property string $date_stop
 * @property int $row
 * @property string $nums
 * @property string $promocode
 * @property int $promolimit
 * @property int $afterpay
 * @property string $info
 * @property string $info_ticket
 * @property int $template_id
 * @property int $type
 * @property string $css
 */
class Seats extends \yii\db\ActiveRecord
{



    public static function tableName()
    {
        return 'seats';
    }


    public function rules()
    {
        return [
            [['event_id', 'name', 'price'], 'required'],
            [['event_id', 'count', 'price', 'row', 'promolimit', 'afterpay', 'template_id', 'type'], 'integer'],
            [['info', 'info_ticket','css','date_start','date_stop'], 'string'],
            ['name', 'match', 'pattern'=>'~^[a-zА-Яа-яёЁ,-_+-—;.*#()&!@=: ]+$~u','message'=>'Используйте только буквы и пробелы'],
            [['name', 'sec', 'nums', 'promocode'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Дата события',
            'name' => 'Название билета',
            'count' => 'Кол-во',
            'price' => 'Стоимость',
            'sec' => 'Секция',
            'row' => 'Ряд',
            'nums' => 'Места 1,3-5 (без пробелов!)',
            'promocode' => 'Промо код',
            'promolimit' => 'Лимит(макс. кол-во за одну покупку)',
            'afterpay' => 'Оплата просроченной брони',
            'info' => 'Подпись к названию билета',
            'template_id' => 'Template Id',
            'type' => 'тип билета',
            'css' => 'css',
        ];
    }


    public static function SortSeats($event)
    {   

        // Найдем секции
        $secs = Seats::find()->select('sec,css')->where(['event_id' => $event->id])->andWhere('`row` > 0')->groupBy('sec')->orderBy(['sec'=>SORT_ASC, 'row'=>SORT_ASC])->all();
        $all_sec = Null;
        foreach ($secs as $sec) {
            // Найдем ряды
            $rows = Seats::find()->where(['event_id' => $event->id, 'sec' => $sec->sec])->andWhere('`row` > 0')->groupBy('row')->orderBy(['sec'=>SORT_ASC, 'row'=>SORT_ASC])->all();
            $all_rows = null;
            foreach ($rows as $row) {
                // В каждом ряду найдем все места
                $seat_type = Seats::find()->where(['event_id' => $event->id, 'row' => $row->row])->all();
                $sq = null;
                foreach ($seat_type as $st) {
                    // Найдем купленные билеты
                    $busy = Tickets::find()->select('seat')->where(['event_id' => $event->id, 'seat_id' => $st->id])->andWhere('status > 0')->andWhere('seat > 0')->asArray()->all();
                    $busy_ids = ArrayHelper::getColumn($busy, 'seat');
                    $count_seat = 0;
                    $seat_in_row = null;
                    // разобъем места написанные через запятую
                    $exp_nums = explode(",", $st->nums);
                    foreach ($exp_nums as $n) {
                        // проверим нет ли дефиса
                        $defis = strpos($n,'-');
                        if ($defis) {
                            // если есть разобьем строку с дефисом
                            $start_end = explode("-", $n);
                            // восстановим все номера и первый тоже
                            for ($i = $start_end[0]; $i <= $start_end[1]; $i++) {
                                // Занято ли место
                                if (in_array($i, $busy_ids)) {
                                    $sq[$i] = ['id' => $st->id, 'name' => $st->name, 'price' => 'busy', 'row' => $st->row, 'css' => $st->css];
                                } else {
                                    $sq[$i] = ['id' => $st->id, 'name' => $st->name, 'price' => $st->price, 'row' => $st->row, 'css' => $st->css];
                                }
                                if ($st->price >= 0) { $count_seat++; }
                            }
                        } else {
                            // Занято ли место
                            if (in_array($n, $busy_ids)) {
                                $sq[$n] = ['id' => $st->id, 'name' => $st->name, 'price' => 'busy', 'row' => $st->row, 'css' => $st->css];
                            } else {
                                $sq[$n] = ['id' => $st->id, 'name' => $st->name, 'price' => $st->price, 'row' => $st->row, 'css' => $st->css];
                            }
                            if ($st->price >= 0) { $count_seat++; }
                        }
                    }
                    $st->count = $count_seat;
                    $st->save();
                }
                ksort($sq);
                $all_rows[$row->row] = $sq;
            }
            $all_sec[] = array('sec' => $sec->sec, 'row' => $all_rows , 'css' => $sec->css) ;
        }
        return $all_sec;
    }


    // Уникальные цены
    public static function ColorSeats($event)
    {
        $rows = Seats::find()->select('price AS p')->where(['event_id' => $event->id])->andWhere('`row` > 0')->groupBy('price')->asArray()->all();
        array_push($rows, array('p' => 'busy'));
        return $rows;
    }


    public  function getEvents()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }


    public  function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }


    // public function getTickets()
    // {
    //     return $this->hasMany(Tickets::className(), ['seat_id' => 'id'])->onCondition(['tickets.status' => [1,2,3,4,5,6,7]]);
    // }
}
