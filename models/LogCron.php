<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_cron".
 *
 * @property int $id
 * @property string $url
 * @property string $result
 * @property int $status 1 = хорошо, 2 = плохо
 * @property string $create_at
 */
class LogCron extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_cron';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer'],
            [['create_at'], 'safe'],
            [['url', 'result'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'result' => 'Result',
            'status' => 'Status',
            'create_at' => 'Create At',
        ];
    }

    // Пишем лог в базу
    public static function setLog($url, $result, $status)
    {
        $logcron = new LogCron();
        $logcron->url = $url;
        $logcron->result = $result;
        $logcron->status = $status;
        $logcron->create_at = date("Y-m-d H:i:s");
        if ($logcron->save()) {
        }else{
            file_put_contents('test.txt', PHP_EOL.PHP_EOL.Date('d.m.Y H:i:s - лог крон: ').json_encode($logcron->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
        }
    }

}