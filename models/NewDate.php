<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newdate".
 *
 * @property int $id
 * @property string $title
 * @property string $info
 * @property int $status
 * @property string $link
 */
class Newdate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newdate';
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
