<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ban_ip".
 *
 * @property int $id
 * @property string $ip
 * @property string $create_at
 * @property int $status 0 = открыт, 1 = забанен
 */
class Ban extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ban_ip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['create_at'], 'safe'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'IP',
            'create_at' => 'Create At',
            'status' => 'Статус',
        ];
    }


    // Заблокировать IP
    public function Ip($ip)
    {
        $ban = new Ban();
        $ban->ip = $ip;
        $ban->create_at = date("Y-m-d H:i:s");
        $ban->status = 1;
        if (!$ban->save()) {
            file_put_contents('test.txt', PHP_EOL .'Бан ' . json_encode($ticket->getErrors(),JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
        }
    }
    
}
