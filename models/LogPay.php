<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_pay".
 *
 * @property int $id
 * @property string $order_id
 * @property string $text
 * @property string $ip
 * @property string $create_at
 * @property int $status
 */
class LogPay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_pay';
    }

    public $group_date;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['order_id', 'text', 'status'], 'required'],
            [['ip', 'text', 'order_id'], 'string'],
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
            'order_id' => 'Order ID',
            'text' => 'Text',
            'ip' => 'IP',
            'create_at' => 'Create At',
            'status' => '1 = хорошо, 2 = плохо',
        ];
    }


    // Пишем лог в базу
    public static function setLog($order_id, $text, $ip, $status): void
    {
        $logpay = new LogPay();
        $logpay->order_id = $order_id;
        $logpay->text = $text;
        $logpay->ip = $ip;
        $logpay->create_at = date("Y-m-d H:i:s");
        $logpay->status = $status;
        $logpay->save();
    }


    // Проверим IP на DDos
    public function checkIp($ip)
    {
        // Проверим заблокирован ли этот IP
        if (!empty(Ban::find()->where(['ip' => $ip])->one())) { return 2; }

        // Проверим запросы с этого IP
        $now = date("Y-m-d H:i:s");
        $sec = date("Y-m-d H:i:s",strtotime("-2 seconds"));
        $min = date("Y-m-d H:i:s",strtotime("-5 minute"));

        // Если за последние 5 минуты было более 60 запросов
        $logpay_all = LogPay::find()->where(['ip' => $ip])->andWhere(['between', 'create_at', $min, $now ])->count();
        if ($logpay_all > 60) { Ban::Ip($ip); Vk::Send('DDoS! '.$ip, [721832, 90794]); return 1; }

        // Если за 2 секунды было более 5 запросов паузим ответ на 5 секунд
        $logpay_min = LogPay::find()->where(['ip' => $ip])->andWhere(['between', 'create_at', $sec, $now ])->count();
        if ($logpay_min > 15) {
            sleep(5); 
        }
        LogPay::setLog('0', 'DDoS', $ip, 2);
    }


    public function getTicket()
    {
        return $this->hasOne(Tickets::className(), ['order_id' => 'order_id']);
    }
}
