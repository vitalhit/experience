<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "band_event".
 *
 * @property int $id
 * @property int $band_id
 * @property int $pquote_id
 */
class BandPquote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'band_pquote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['band_id', 'pquote_id'], 'required'],
            [['band_id', 'pquote_id'], 'integer'],
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
            'pquote_id' => 'PQuote ID',
        ];
    }

    public function getBand()
    {
        return $this->hasOne(Band::className(), ['id' => 'band_id']);
    }
}
