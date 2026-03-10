<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bills".
 *
 * @property integer $id
 * @property string $name
 * @property integer $summa
 * @property string $info
 * @property string $pole
 */
class Bills extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'summa'], 'required'],
            [['summa'], 'integer'],
            [['info'], 'string'],
            [['name', 'pole'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'summa' => 'Сумма',
            'info' => 'Info',
            'pole' => 'Pole',
        ];
    }
}
