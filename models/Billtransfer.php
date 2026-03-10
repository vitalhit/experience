<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "billtransfer".
 *
 * @property integer $id
 * @property string $date
 * @property string $name
 * @property integer $bill_id_from
 * @property integer $bill_id_to
 * @property integer $bfrom
 * @property integer $bto
 * @property string $info
 * @property string $summa
 * @property integer $cat
 * @property integer $type
 */
class Billtransfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billtransfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['date'], 'safe'],
        [['bill_id_from', 'bill_id_to', 'cat', 'summa', 'type'], 'integer'],
        [['info'], 'string'],
        [['name', 'bfrom', 'bto'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        'id' => 'ID',
        'date' => 'Date',
        'name' => 'Название',
        'bill_id_from' => 'Со счета',
        'bill_id_to' => 'На счет',
        'bfrom' => 'Bfrom',
        'bto' => 'Bto',
        'info' => 'Info',
        'summa' => 'Сумма',
        'cat' => 'Cat',
        'type' => 'Тип',
        ];
    }

    public function getBillFrom()
    {
        return $this->hasOne(Bills::className(), ['id' => 'bill_id_from']);
    }

    public function getBillTo()
    {
        return $this->hasOne(Bills::className(), ['id' => 'bill_id_to']);
    }

}
