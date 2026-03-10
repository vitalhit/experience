<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buys".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $count
 * @property integer $price
 * @property string $date
 * @property integer $manager_id
 * @property string $info
 */
class Buys extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'count', 'price'], 'required'],
            [['good_id', 'count', 'price', 'manager_id'], 'integer'],
            [['date'], 'safe'],
            [['info'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'good_id' => 'Товар',
            'count' => 'Количество',
            'price' => 'Сумма',
            'date' => 'Дата',
            'manager_id' => 'Администратор',
            'info' => 'Info',
        ];
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'good_id']);
    }
}
