<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newdates".
 *
 * @property int $id
 * @property string $title
 * @property string $info
 * @property int $status
 * @property string $link
 */
class Newdates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newdates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status','place_id','biblioevent_id',], 'integer'],
            [['title','info','link','date','date_closed'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'status',
            'info' => 'info',
            'title' => 'title',
        ];
    }
    
}
