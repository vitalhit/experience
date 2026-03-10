<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_user".
 *
 * @property int $id
 * @property int $company_id
 * @property int $user_id
 * @property string $create_at
 * @property int $bywho
 */
class CompanyUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'user_id', 'bywho'], 'required'],
            [['company_id', 'user_id', 'bywho'], 'integer'],
            [['create_at'], 'safe'],
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
            'create_at' => 'Create At',
            'bywho' => 'Bywho',
        ];
    }


    // public function getPerson()
    // {
    //     return $this->hasOne(Persons::className(), ['id' => 'person_id'])->viaTable('user', ['id' => 'user_id']);
    // }

    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['user_id' => 'user_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getRole()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'user_id']);
    }
}
