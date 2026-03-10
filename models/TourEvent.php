<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tour_event".
 *
 * @property int $id
 * @property int $tour_id
 * @property int $event_id
 */
class TourEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tour_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tour_id', 'event_id'], 'required'],
            [['tour_id', 'event_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tour_id' => 'Tour ID',
            'event_id' => 'Event ID',
        ];
    }

    public function getTour()
    {
        return $this->hasOne(Tour::className(), ['id' => 'tour_id']);
    }
}
