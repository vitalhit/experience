<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_person".
 *
 * @property int $id
 * @property int $company_id
 * @property int $person_id
 * @property string $create_at
 * @property int $bywho
 */
class CompanyPerson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'person_id'], 'required'],
            [['company_id', 'person_id', 'bywho'], 'integer'],
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
            'company_id' => 'ID Компании',
            'person_id' => 'ID Персоны',
            'create_at' => 'Создан',
            'bywho' => 'Кем',
        ];
    }

    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id']);
    }
}
