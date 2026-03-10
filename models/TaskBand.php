<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_client".
 *
 * @property int $id
 * @property int $task_id
 * @property int $band_id
 * @property string $info
 */
class TaskBand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_band';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id','band_id'], 'required'],
            [['task_id', 'band_id'], 'integer'],
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
            'band_id' => 'band id',
            'info' => 'info',
        ];
    }

}
