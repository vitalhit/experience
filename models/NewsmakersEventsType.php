<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "newsmakers_events_type".
 *
 * @property interger $id
 * @property string $name
 * @property string $info
 * @property interger $deleted
 */
class NewsmakersEventsType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsmakers_events_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['info'], 'string'],
            [['deleted'], 'integer'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'info' => 'Описание',
            'Deletd' => 'Удален',
        ];
    }





}
