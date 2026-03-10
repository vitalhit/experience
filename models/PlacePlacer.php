<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "band_event".
 *
 * @property int $id
 * @property int $place_id
 * @property int $placer_id
 * @property string $info
 * @property int $deleted
 */
class PlacePlacer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'place_placer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['place_id', 'placer_id'], 'required'],
            [['place_id', 'placer_id', 'deleted'], 'integer'],
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
            'place_id' => 'Band ID',
            'placer_id' => 'Event ID',
            'info' => 'info: должность и т.д.',
            'deleted' => 'Удален',
        ];
    }

    public function getPlace()
    {
        return $this->hasOne(Places::className(), ['id' => 'place_id']);
    }
    public function getPlaces()
    {
        return $this->hasMany(Places::className(), ['id' => 'place_id']);
    }
}
