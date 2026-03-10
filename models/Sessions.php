<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "sessions".
 *
 * @property int $id
 * @property int $event_id
 * @property string $time_start
 * @property string $time_end
 * @property int $max_tickets
 * @property int $booked_tickets
 * @property float $price_multiplier
 * @property string|null $last_calc_at
 * @property int $status
 * @property string $created_at
 *
 * @property Events $event
 * @property Tickets[] $tickets
 */
class Sessions extends ActiveRecord
{
    public static function tableName()
    {
        return 'sessions';
    }

    public function rules()
    {
        return [
            [['event_id', 'time_start', 'time_end'], 'required'],
            [['event_id', 'max_tickets', 'booked_tickets', 'status'], 'integer'],
            [['price_multiplier'], 'number', 'min' => 0.5, 'max' => 3],
            [['time_start', 'time_end', 'last_calc_at', 'created_at'], 'safe'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    public function getTickets()
    {
        return $this->hasMany(Tickets::className(), ['session_id' => 'id']);
    }

    /**
     * Получить количество свободных мест
     */
    public function getAvailableTickets()
    {
        return $this->max_tickets - $this->booked_tickets;
    }

    /**
     * Проверить, доступен ли сеанс для бронирования
     */
    public function isAvailable()
    {
        return $this->status == 1 && $this->getAvailableTickets() > 0;
    }
}
