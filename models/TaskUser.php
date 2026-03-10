<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_client".
 *
 * @property int $id
 * @property int $task_id
 * @property int $client_id
 * @property string $info
 * @property int $notification 0 - нет  1 — да
 */
class TaskUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id','user_id'], 'required'],
            [['notification'], 'integer'],
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
            'user_id' => 'task user id',
            'info' => 'Информация',
            'notification' => 'Уведомление',
        ];
    }

}
