<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingredients".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $item_id
 * @property integer $count
 * @property string $info
 */
class Ingredients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ingredients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'item_id', 'count'], 'required'],
            [['good_id', 'item_id', 'count'], 'integer'],
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
            'item_id' => 'Ингредиент',
            'count' => 'Количество гр.',
            'info' => 'Комментарий',
        ];
    }
    
    public function getItems()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }
}
