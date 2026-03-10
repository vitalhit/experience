<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_client".
 *
 * @property int $id
 * @property int $task_id
 * @property int $biblioevent_id
 * @property string $info
 */

class TaskBiblioevent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_biblioevent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id','biblioevent_id'], 'required'],
            [['info'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'task id',
            'biblioevent_id' => 'id события',
            'info' => 'Информация',
        ];
    }

}
