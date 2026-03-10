<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_finance".
 *
 * @property int $id
 * @property int $event_id
 * @property int $fiesta
 * @property int $ponominalu
 * @property int $kassir
 * @property int $2do2go
 * @property int $peterburg2
 * @property int $jazzmap
 */
class EventFinance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_finance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id' ], 'required'],
            [[ 'event_id', 'fiesta', 'ponominalu', 'kassir', 'kassir', '2do2go', 'peterburg2', 'jazzmap' ], 'integer'],
            ////

            [['date'], 'safe'],
            [['info'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'ID компании',
            'event_id' => 'ID Даты события',
            'task_id' => 'ID Задачи',
            'name' => 'Название',
            'from_user_id' => 'От пользователя',
            'to_user_id' => 'К пользователю (персона)',
            'from_contragent' => 'От контрагента',
            'to_contragent' => 'К контрагенту',
            'date' => 'Дата',
            'summa' => 'Сумма',
            'info' => 'Инфо',
            'status' => 'Статус',
            'state' => 'Состояние',
            'deleted' => 'Удалена',
        ];
    }
    

    public function getPersonf()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id'])->viaTable('user', ['id' => 'from_user_id']);
    }

    public function getPersonto()
    {
        return $this->hasOne(Persons::className(), ['id' => 'to_user_id']);
    }
    
    public function getContragent()
    {
        return $this->hasOne(Contragent::className(), ['id' => 'from_contragent']);
    }
    
    public function getContragentto()
    {
        return $this->hasOne(Contragent::className(), ['id' => 'to_contragent']);
    }
}
