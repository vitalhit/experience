<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newsmakers".
 *
 * @property int $id
 * @property int $newsmaker_id
 * @property int $event_id
 * @property int $status
* @property int $type_id
 * @property string $info
 * @property string $date_create

 */
class NewsmakersEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsmakers_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'type_id', 'newsmaker_id', 'status'], 'integer'],
            [['info'], 'string'],
            [['date_create'], 'safe'],
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
            'newsmaker_id' => 'Newsmaker ID',
            'status' => 'status',
        ];
    }

}
