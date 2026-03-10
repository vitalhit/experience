<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rents".
 *
 * @property int $id
 * @property string $order_id
 * @property int $room_id
 * @property int $person_id
 * @property int $company_id
 * @property string $date
 * @property string $start
 * @property string $end
 * @property int $summa
 * @property int $type
 * @property string $info
 * @property string $create_at
 * @property int $status
 */
class Rents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rents';
    }

    public $nal;
    public $bez;
    public $gr;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id', 'summa'], 'required'],
            [['room_id', 'person_id', 'company_id', 'summa', 'type', 'status'], 'integer'],
            [['date', 'start', 'end', 'create_at'], 'safe'],
            [['order_id', 'info'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Номер заказа',
            'room_id' => 'Зал',
            'person_id' => 'ID гостя',
            'company_id' => 'ID компании',
            'date' => 'Дата создания заявки',
            'start' => 'Начало аренда',
            'end' => 'Окончание аренды',
            'summa' => 'Сумма',
            'type' => '1 - Наличка, 2 - Безнал, 4 - GR',
            'info' => 'Служебная информация',
            'create_at' => 'Создано',
            'status' => 'Статус',
        ];
    }
    
    public function getRooms()
    {
        return $this->hasOne(Rooms::className(), ['id' => 'room_id']);
    }
    
    public function getPersons()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id']);
    }
}
