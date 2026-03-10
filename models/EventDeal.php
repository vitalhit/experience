<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_deal".
 *
 * @property int $id
 * @property int $event_id
 * @property int $deal_id
 * @property string $info
 * @property int $media_id
 * @property int $newsmaker_id
 * @property int $profit
 * @property int $percent
 * @property int $status
 * @property string $date_create
 * @property string $date_closed
 
 */
class EventDeal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_deal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'percent'], 'required'],
            [['deal_id', 'event_id', 'media_id', 'newsmaker_id', 'profit', 'percent', 'status'], 'integer'],
            [['date_create', 'date_closed'], 'safe'],
            [['info'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'ID Даты события',
            'deal_id' => 'deal ID',
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
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    
}
