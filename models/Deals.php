<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deals".
 *
 * @property int $id
 * @property int $media_id
 * @property int $newsmaker_id
 * @property int $profit
 * @property string $serv_info
 * @property string $info
 * @property string $date_create
 * @property string $date_start
 * @property string $date_end
 * @property string $date_closed
 
 */
class Deals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['event_id', 'percent'], 'required'],
            [['media_id',  'newsmaker_id', 'profit',], 'integer'],
            [['date_create', 'date_closed','date_start','date_end'], 'safe'],
            [['info','title','serv_info'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            //'event_id' => 'ID Даты события',
           // 'deal_id' => 'deal ID',
            'info' => 'информация',
            'media_id' => 'media ID',
            'newsmaker_id' => 'Newsmaker ID',
            'status' => 'Статус',
            'date_create' => 'Дата создания',
            'date_closed' => 'Дата закрытия',
        ];
    }

    public function getEvent()
    {
      //  return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    
}
