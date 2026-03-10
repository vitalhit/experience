<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "secretcode".
 *
 * @property int $id
 * @property string $title
 * @property string $info
 * @property string $code
 * @property int $event_id
 * @property int $biblioevent_id
 * @property int $discount
 * @property int $percent
 */
class Secretcode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'secretcode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code' ], 'required'],
            [['title', 'info'], 'string', 'max' => 255],
            [['event_id', 'biblioevent_id','discount','percent'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'заголовок',
            'info' => 'Информация',
            'code' => 'Промо-код',
            'event_id' => 'event id',
            'biblioevent_id' => 'biblioevent id',
            'discount' => 'Скидка/рубли',
            'percent' => 'Скидка/проценты',

        ];
    }
}
