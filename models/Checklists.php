<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklists".
 *
 * @property int $id
 * @property int $task_id
 * @property string $site
 * @property string $summery
 * @property string $info
 * @property int @status
 * @property int $event_id
 * @property string $owner
 * @property string $creator
 * @property string $quality_manager
 * @property int $delete
 */
class Checklists extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklists';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id','task_id','public_vk_id','status'], 'integer'],
            [['site','summery','creator','owner','info','quality_manager'], 'string'],
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
