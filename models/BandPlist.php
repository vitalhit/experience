<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "band_plist".
 *
 * @property int $id
 * @property int $band_id
 * @property int $event_id
 * @property int $deleted
 */
class BandPlist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'band_plist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['band_id', 'event_id'], 'required'],
            [['band_id', 'event_id','deleted'], 'integer'],
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
