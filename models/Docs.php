<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "docs".
 *
 * @property int $id
 * @property int $company_id
 * @property int $user_id
 * @property int $task_id
 * @property string $date Для какого месяца
 * @property string $image
 * @property string $create_at
 * @property int $status
 * @property int $verify_user_id кто проверил
 * @property string $verify_create_at когда проверил
 * @property string $info
 */
class Docs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'docs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'user_id', 'image'], 'required'],
            [['company_id', 'user_id', 'task_id', 'status', 'verify_user_id'], 'integer'],
            [['date', 'create_at', 'verify_create_at'], 'safe'],
            [['image', 'info'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'user_id' => 'User ID',
            'task_id' => 'ID Task',
            'date' => 'Date',
            'image' => 'Image',
            'create_at' => 'Create At',
            'status' => 'Status',
            'verify_user_id' => 'Verify User ID',
            'verify_create_at' => 'Verify Create At',
            'info' => 'Info',
        ];
    }
    
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['user_id' => 'user_id']);
    }
    public function getVerify()
    {
        return $this->hasOne(Persons::className(), ['user_id' => 'verify_user_id']);
    }
}
