<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tools_events".
 *
 * @property int $id
 * @property int $tool_id
 * @property int $event_id
 * @property int $status
 * @property string $info
 * @property string $date_create

 */
class ToolsEvents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tools_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'tool_id', 'status'], 'integer'],
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
            'tool_id' => 'Tool ID',
            'status' => 'status',
        ];
    }

}
