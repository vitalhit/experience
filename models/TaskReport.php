<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks_type".
 *
 * @property int $id
 * @property int $task_id
 * @property int $event_id
 * @property string $info
 * @property string $info_public
 * @property int $user_id
 * @property int $client_id
 * @property int $minutes
 * @property string $date
 */
class TaskReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {   
        return [
            [['info'], 'required'],
            [['info','date','info_public'], 'string'],
            [['task_id','event_id','user_id','client_id','minutes'], 'integer']

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Тип',
        ];
    }
}
