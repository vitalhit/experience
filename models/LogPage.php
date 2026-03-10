<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_page".
 *
 * @property int $id
 * @property string $url
 * @property int $status 1 - хорошо, 2 - плохо
 * @property string $create_at
 * @property int $user_id
 * @property string $info
 */
class LogPage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'status'], 'required'],
            [['status', 'user_id'], 'integer'],
            [['create_at'], 'safe'],
            [['info'], 'string'],
            [['url'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'create_at' => 'Create At',
            'user_id' => 'User ID',
            'info' => 'Info',
        ];
    }

    // Пишем лог в базу
    public static function setLog($url, $status, $info = false)
    {
        $logpage = new LogPage();
        if (!empty(Yii::$app->user->id)) { $logpage->user_id = Yii::$app->user->id; }
        $logpage->url = $url;
        $logpage->status = $status;
        $logpage->create_at = date("Y-m-d H:i:s");
        if (!empty($info)) { $logpage->info = $info; }
        $logpage->save();
    }
}
