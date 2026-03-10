<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_payments".
 *
 * @property int $id
 * @property int $company_id
 * @property int $user_id
 * @property int $summa
 * @property string $info
 * @property string $serv_info
 * @property string $date
 * @property string $create_at
 * @property int $status
 * @property int $event_id
 */
class CompanyPayments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'user_id', 'summa', 'status'], 'required'],
            [['company_id', 'user_id', 'summa', 'status', 'event_id'], 'integer'],
            [['date', 'create_at'], 'safe'],
            [['info', 'serv_info'], 'string', 'max' => 255],
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
            'summa' => 'Сумма',
            'info' => 'Служебная информация',
            'date' => 'Дата превода Y-m-d',
            'create_at' => 'Создан',
            'status' => 'Статус 1-Ожидает отправки, 2-Отправлен, 3-Получен партнером',
        ];
    }

    public static function Sum($c_ids, $status, $start, $end)
    {
        return CompanyPayments::find()->where(['company_id' => $c_ids, 'status' => $status])->andWhere(['between', 'create_at', $start, $end ])->sum('summa');
    }

    public function Ostatok($c_ids, $start, $end)
    {
        $t = Tickets::Sum(Events::getIds($c_ids), 5, $start, $end); // сумма билетов
        $v = Tickets::Sum(Events::getIds($c_ids), 7, $start, $end); // сумма возвратов
        $p = CompanyPayments::Sum($c_ids, 3, $start, $end); // сумма выплат
        return $t - round($t * 0.06) - round($v * 0.1) - $p;
    }

    public function getCompanies()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }

    public function getPersons()
    {
        return $this->hasOne(Persons::className(), ['user_id' => 'user_id']);
    }
}
