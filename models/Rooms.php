<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $company_id
 * @property int $place_id
 * @property int $user_id
 * @property int $name
 * @property string $description
 * @property string $image
 * @property string $info
 * @property string $time_start
 * @property string $time_end
 * @property string $time_step
 * @property int $money
 * @property int $status
 */
class Rooms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rooms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'place_id', 'money', 'company_id'], 'required'],
            [['company_id', 'place_id', 'user_id', 'money', 'status'], 'integer'],
            [['name', 'description', 'image', 'info'], 'string'],
            [['time_start', 'time_end', 'time_step'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Компания',
            'place_id' => 'Площадка',
            'user_id' => 'Менеджер',
            'name' => 'Название',
            'description' => 'Описание',
            'image' => 'Картинка',
            'status' => 'Статус',
            'time_start' => 'Аренда С',
            'time_end' => 'Аренда По',
            'time_step' => 'Шаг времени',
            'money' => 'Стоимость шага',
            'info' => 'Служебная информация',
        ];
    }
    
    public function getPlaces()
    {
        return $this->hasOne(Places::className(), ['id' => 'place_id']);
    }
    
    public function getRents()
    {
        return $this->hasMany(Rents::className(), ['room_id' => 'id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
