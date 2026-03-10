<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $person_id
 * @property string $order_id
 * @property string $theme
 * @property string $text
 * @property string $create_at
 * @property int $status
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['person_id', 'theme', 'text', 'status'], 'required'],
            [['person_id', 'status'], 'integer'],
            [['text'], 'string'],
            [['create_at'], 'safe'],
            [['theme', 'order_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'person_id' => 'ID Гостя',
            'order_id' => 'ID продукта',
            'theme' => 'Тема',
            'text' => 'Текст',
            'create_at' => 'Дата',
            'status' => 'Статус',
        ];
    }

    // Записываем в базу, что письмо отправлено
    public static function saveMail($person_id, $order_id, $theme, $text, $type)
    {
        $message = new Messages(); 
        $message->person_id = $person_id;
        $message->order_id = $order_id;
        $message->theme = $theme;
        $message->text = $text;
        $message->create_at = date("Y-m-d H:i:s");
        $message->status = 1;
        $message->type = $type;
        $message->save();

        return $message;
    }



    // Записываем в базу, что НАПОМИНАНИЕ отправлено
    public function saveRememberMail($person, $order_id, $theme)
    {
        $message = new Messages(); 
        $message->person_id = $person->id;
        $message->order_id = $order_id;
        $message->theme = $theme;
        $message->text = 'напоминание';
        $message->create_at = date("Y-m-d H:i:s");
        $message->status = 1;
        $message->type = 1;
        $message->save();
        return $message;
    }

    public function getTicket()
    {
        return $this->hasOne(Tickets::className(), ['order_id' => 'order_id']);
    }
}
