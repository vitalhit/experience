<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "band_event".
 *
 * @property int $id
 * @property int $band_id
 * @property int $event_id
 */
class BandEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'band_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['band_id', 'event_id'], 'required'],
            [['band_id', 'event_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'band_id' => 'Band ID',
            'event_id' => 'Event ID',
        ];
    }

    public function getBand()
    {
        return $this->hasOne(Band::className(), ['id' => 'band_id']);
    }
}
