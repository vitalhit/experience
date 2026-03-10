<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "biblioevent_event".
 *
 * @property int $id
 * @property int $biblioevent_id
 * @property int $event_id
 * @property int $deleted
 */
class BiblioeventEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'biblioevent_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['biblioevent_id', 'event_id'], 'required'],
            [['biblioevent_id', 'event_id','deleted'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'biblioevent_id' => 'Biblioevent ID',
            'event_id' => 'Event ID',
            'deleted' => 'Удален: 0 - нет, 1 - да',
        ];
    }

    
}
