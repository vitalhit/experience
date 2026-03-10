<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contests".
 *
 * @property int $id
 * @property int $event_id
 * @property string $link
 * @property string $date_create
 * @property string $date_end
 * @property string $info
 * @property string $public_vk_id
 * @property int $status
 */
class Contests extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id'], 'required'],
            [['event_id','public_vk_id','status'], 'integer'],
            [['link','date_create','date_end','info'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
        ];
    }

    public function getBand()
    {
        return $this->hasOne(Band::className(), ['id' => 'band_id']);
    }
}
