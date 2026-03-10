<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "removes".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $count
 * @property integer $price
 * @property string $date
 * @property integer $manager_id
 * @property string $info
 */
class Removes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'removes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'count', 'price'], 'required'],
            [['item_id', 'count', 'price', 'manager_id'], 'integer'],
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
            'item_id' => 'Item ID',
            'count' => 'Count',
            'price' => 'Price',
            'date' => 'Date',
            'manager_id' => 'Manager ID',
            'info' => 'Info',
        ];
    }

    public function getItems()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }

    public function getSells()
    {
        return $this->hasOne(Sells::className(), ['id' => 'item_id']);
    }
}
