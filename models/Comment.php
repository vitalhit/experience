<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для таблицы "comments".
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_biblioevent
 * @property int|null $id_event
 * @property string|null $title
 * @property string|null $text
 * @property string|null $date
 * @property int|null $id_task
 * @property int|null $commercial
 * @property string|null $order_id
 * @property int|null $author_id user_id / person_id того, кто пишет
 * @property int|null $author_type 1=гость, 2=гид, 3=менеджер
 * @property string|null $body
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $read_at когда прочитано
 * @property int|null $deleted
 */
class Comment extends ActiveRecord
{
    const AUTHOR_TYPE_GUEST = 1;
    const AUTHOR_TYPE_GUIDE = 2;
    const AUTHOR_TYPE_MANAGER = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_biblioevent', 'id_event', 'id_task', 'commercial', 'author_id', 'author_type', 'deleted'], 'integer'],
            [['title', 'text', 'body'], 'string'],
            [['date', 'created_at', 'updated_at', 'read_at'], 'safe'],
            [['body'], 'string', 'max' => 1000],
            [['order_id'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'ID пользователя',
            'id_biblioevent' => 'Событие (biblioevent)',
            'id_event' => 'Дата (event)',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'date' => 'Дата',
            'id_task' => 'Задача',
            'commercial' => 'Коммерческий',
            'order_id' => 'Номер заказа',
            'author_id' => 'Автор (person_id)',
            'author_type' => 'Тип автора',
            'body' => 'Сообщение',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
            'read_at' => 'Прочитано',
            'deleted' => 'Удалён',
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(Persons::className(), ['id' => 'author_id']);
    }

    public function getBiblioevent()
    {
        return $this->hasOne(Biblioevents::className(), ['id' => 'id_biblioevent']);
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'id_event']);
    }

    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'id_task']);
    }

    /**
     * Список подписей для author_type.
     */
    public static function authorTypeLabels(): array
    {
        return [
            self::AUTHOR_TYPE_GUEST => 'Гость',
            self::AUTHOR_TYPE_GUIDE => 'Гид',
            self::AUTHOR_TYPE_MANAGER => 'Менеджер',
        ];
    }
}
