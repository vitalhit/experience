<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tickets_return".
 *
 * @property int $id
 * @property int $ticket_id
 * @property string $text
 * @property int $owner
 * @property int $creator
 * @property int $status
 * @property string $condtions
 * @property string $date_create
 * @property string $date_done
 * @property int $price
 * @property int $price_for_eventer
 * @property int $price_for_person
 * @property int $price_for_igoevent
 * @property int $deleted
 * @property int $event_id
 */

class TicketsReturn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tickets_return';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            [['ticket_id', 'owner', 'creator', 'status', 'price', 'price_for_eventer', 'price_for_person', 'price_for_igoevent', 'deleted', 'event_id'], 'integer'],
            [['date_done','date_create'], 'safe'],
            [['text','conditions',], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */ 
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'ticket id',
            'text' => 'Причина возврата',
            'owner' => 'id владельца билета',
            'creator' => 'id создателя возврата',
            'status' => 'статус',
            'conditions' => 'условия возврата',
            'date_create()' => 'дата создания заявки',
            'date_done' => 'дата осуществления возврата',
            'price' => 'стоимость билета',
            'price_for_eventer' => 'затраты организатора',
            'price_for_person' => 'затрата гостя',
            'price_for_igoevent' => 'затрата igoevent',
            'deleted' => 'Удалена',
        ];
    }
    

    public function getPersonf()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id'])->viaTable('user', ['id' => 'from_user_id']);
    }

    public function getPersonto()
    {
        return $this->hasOne(Persons::className(), ['id' => 'to_user_id']);
    }
    
    public function getContragent()
    {
        return $this->hasOne(Contragent::className(), ['id' => 'from_contragent']);
    }
    
    public function getContragentto()
    {
        return $this->hasOne(Contragent::className(), ['id' => 'to_contragent']);
    }
}
