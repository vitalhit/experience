<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "experience_orders".
 *
 * @property int $id
 * @property string $order_number
 * @property int $company_id
 * @property float $total_amount
 * @property float $prepayment_amount
 * @property int $prepayment_percent
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Companies $company
 * @property Tickets[] $tickets
 */
class ExperienceOrder extends ActiveRecord
{
    const STATUS_WAITING = 0;       // Ожидает оплаты
    const STATUS_PAID = 5;          // Оплачен
    const STATUS_REFUND = 7;       // Возврат
    const STATUS_REGISTRATION = 1;  // Регистрация (бесплатно)

    public static function tableName()
    {
        return 'experience_orders';
    }

    public function rules()
    {
        return [
            [['company_id', 'total_amount', 'prepayment_amount', 'prepayment_percent'], 'required'],
            [['company_id', 'status', 'prepayment_percent'], 'integer'],
            [['total_amount', 'prepayment_amount'], 'number'],
            [['order_number'], 'string', 'max' => 20],
            [['order_number'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert && empty($this->order_number)) {
                $this->order_number = $this->generateOrderNumber();
            }
            return true;
        }
        return false;
    }

    private function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
    }

    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }

    public function getTickets()
    {
        return $this->hasMany(Tickets::className(), ['experience_order_id' => 'id']);
    }
}
