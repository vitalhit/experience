<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string $page
 * @property int $user_id
 * @property int $task_id
 * @property int $for_user_id Виден только id юзеру
 * @property string $text
 * @property string $create_at
 * @property int $status 1 = новый, 2 = в работе, 3 = отменен, 4 = готов, 5 = напомнить
 * @property string $info
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'task_id', 'for_user_id', 'status'], 'integer'],
            [['text', 'info'], 'string'],
            [['create_at'], 'safe'],
            [['page'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page' => 'Страница',
            'user_id' => 'Юрез',
            'task_id' => 'ID таска',
            'for_user_id' => 'Ответственный user_id',
            'text' => 'Вопрос',
            'create_at' => 'Create At',
            'status' => 'Статус',
            'info' => 'Служебная информация',
        ];
    }
    public function getPersons()
    {
        return $this->hasOne(Persons::className(), ['user_id' => 'user_id']);
    }
}
