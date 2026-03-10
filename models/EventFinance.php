<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_finance".
 *
 * @property int $id
 * @property int $company_id
 * @property int $event_id
 * @property int $smena_id
 * @property int $task_id
 * @property int $project_id
 * @property string $name
 * @property int $from_user_id
 * @property int $to_user_id
 * @property int $client_id
 * @property int $employee_id
 * @property int $from_contragent
 * @property int $to_contragent
 * @property string $date
 * @property string $date_create
 * @property string $bill_name
 * @property string $date_payment
 * @property string $act_number
 * @property string $doc_number
 * @property string $date_act
 * @property string $date_doc
 * @property int $user_create
 * @property int $summa
 * @property int $summa_k
 * @property string $info
 * @property string $info_reason
 * @property string $info_pay
 * @property string $info_pay_band
 * @property string $info_pay_machine
 * @property string $info_doc
 * @property int $status
 * @property int $status_logistics
 * @property int $state
 * @property int $logistics
 * @property string $logistics
 * @property string $contract_template_id
 * @property string $serv_info
 * @property int $deleted
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
            [['company_id', 'summa'], 'required'],
            [['company_id', 'event_id', 'smena_id', 'task_id', 'project_id', 'from_user_id', 'to_user_id', 'client_id', 'employee_id', 'from_contragent', 'to_contragent', 'summa', 'status', 'status_logistics', 'state', 'logistics', 'logistics_our', 'deleted','user_create'], 'integer'],
            [['date', 'date_payment','date_act','date_doc','date_create'], 'safe'],
            [['info', 'info_reason', 'info_pay','info_pay_machine','info_pay_bank','info_doc', 'bill_name','act_number','doc_number', 'link','contract_template_id', 'serv_info'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['summa_k'], 'integer', 'max' => '99']
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
            'date' => 'Дата счета',
            'summa' => 'Сумма',
            'summa_k' => 'Сумма(копейки)',
            'info' => 'Инфо',
            'info_reson' => 'Иноф:причина',
            'info_pay_machine' => 'Номер чека',
            'info_pay_bank' => 'Транзакция в банке',
            'status' => 'Статус',
            'status_logistics' => 'Logistics',
            'bill_name' => 'Номер счета/bill_name',
            'state' => 'Состояние',
            'logistics' => 'Орегиналы(их)',
            'logistics_our' => 'Орегиналы(наши)',
            'contract_template_id'=>'Id шаблона договора',
            'serv_info' => 'Служебная информация',
            'deleted' => 'Удалена',
        ];
    }
    

    public function getPersonf()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id'])->viaTable('user', ['id' => 'from_user_id']); 
       // return $this->hasOne(Persons::className(), ['id' => 'from_user_id']); // Виталий исправил 71021 delete
    }

    public function getPersonto()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id'])->viaTable('user', ['id' => 'to_user_id']);
        //return $this->hasOne(Persons::className(), ['id' => 'to_user_id']); // Виталий исправил 71021 delete
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

