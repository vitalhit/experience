<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist_step".
 *
 * @property int $id
 * @property int $checklist_id
 * @property int $priority
 * @property string $title
 * @property string $summery
 * @property string $info
 * @property string @site
 * @property int $user_id
 * @property int $client_id
 * @property int $process_id
 * @property int $deleted
 * @property int $hous_before
 * @property int $hous_after
 */
class Checklists extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist_step';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id','checklist_id','priority','user_id','client_id','process_id','deleted','hous_before','hours_after'], 'integer'],
            [['title','summery','info','site'], 'string'],
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
