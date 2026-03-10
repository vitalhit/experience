<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "band_person".
 *
 * @property int $id
 * @property int $band_id
 * @property int $person_id
 */
class BandPerson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'band_person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['band_id', 'person_id'], 'required'],
            [['band_id', 'person_id'], 'integer'],
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
            'person_id' => 'Person ID',
        ];
    }
}
