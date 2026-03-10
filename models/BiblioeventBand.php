<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "biblioevent_band".
 *
 * @property int $id
 * @property int $band_id
 * @property int $biblioevent_id
 */
class BiblioeventBand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'biblioevent_band';
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

    public function getBiblioevents()
    {
        return $this->hasOne(Biblioevents::className(), ['id' => 'biblioevent_id']);
    }
}
