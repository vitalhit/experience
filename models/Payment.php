<?php

namespace app\models;

/**
 * Информация об оплатах из яндекс кассы "payment".
 *
 * @property int $id
 * @property string $order_id
 * @property string $payment_id
 * @property string $payment_method_id
 * @property string $create_at
 * @property int $status
 */

class Payment extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'payment';
    }


    public function rules()
    {
        return [
            [['order_id', 'payment_id'], 'required'],
            [['status',], 'integer'],
            [['create_at'], 'safe'],
            [['order_id', 'payment_id', 'payment_method_id'], 'string', 'max' => 255],
        ];
    }

}
