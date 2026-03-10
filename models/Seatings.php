<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seatings".
 *
 * @property integer $id
 * @property integer $place_id
 * @property string $name
 * @property string $image
 * @property string $info
 */
class Seatings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seatings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['place_id'], 'required'],
            [['place_id'], 'integer'],
            [['info'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }





    public function upload(){
        if($this->validate()){
            $this->image->saveAs("uploads/{$this->image->baseName}.{$this->image->extension}");
        }else{
            return false;
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'place_id' => 'Место (площадка)',
            'name' => 'Название рассадки',
            'image' => 'Схема зала (необязательно)',
            'info' => 'Служебная информация'
        ];
    }

    public function getPlaces()
    {
        return $this->hasOne(Places::className(), ['id' => 'place_id']);
    }

    public function getSeats()
    {
        return $this->hasMany(Seats::className(), ['seating_id' => 'id']);
    }
}
