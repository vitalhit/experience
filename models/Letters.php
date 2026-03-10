<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "letters".
 *
 * @property int $id
 * @property int $biblioevent_id
 * @property int $type
 * @property string $theme
 * @property string $text
 * @property int $status
 */
class Letters extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'letters';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['biblioevent_id', 'type', 'theme', 'text', 'status'], 'required'],
            [['biblioevent_id', 'type', 'status'], 'integer'],
            [['text'], 'string'],
            [['theme'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'biblioevent_id' => 'ID События',
            'type' => 'Тип',
            'theme' => 'Тема',
            'text' => 'Текст',
            'status' => 'Статус',
        ];
    }
}
