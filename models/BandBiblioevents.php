<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "band_biblioevent".
 *
 * @property int $id
 * @property int $band_id
 * @property int $biblioevent_id
 */
class BandBibliovent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'band_biblioevent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['band_id', 'biblioevent_id'], 'required'],
            [['band_id', 'biblioevent_id'], 'integer'],
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
            'biblioevent_id' => 'Biblioevent ID',
        ];
    }

    public function getBand()
    {
        return $this->hasOne(Band::className(), ['id' => 'band_id']);
    }
}
