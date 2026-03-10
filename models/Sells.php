<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sells".
 *
 * @property integer $id
 * @property integer $smena_id
 * @property integer $user_id
 * @property integer $good_id
 * @property string $date
 * @property string $info
 * @property integer $price
 * @property integer $type
 */
class Sells extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'sells';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'good_id', 'price', 'type', 'count', 'itogo'], 'required'],
			[['smena_id', 'user_id', 'good_id', 'price', 'type', 'count', 'itogo'], 'integer'],
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
			'smena_id' => 'Номер смены',
			'user_id' => 'Гость',
			'good_id' => 'Товар',
			'date' => 'Date',
			'info' => 'Info',
			'price' => 'Price',
			'type' => 'Type',
			'count' => 'Кол-во',
			'itogo' => 'Итого',
		];
	} 

	public function getPersons()
	{
		return $this->hasOne(Persons::className(), ['id' => 'user_id']);
	}
	
	public function getGoods()
	{
		return $this->hasOne(Goods::className(), ['id' => 'good_id']);
	}
}
