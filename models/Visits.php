<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visits".
 *
 * @property integer $id
 * @property integer $smena_id
 * @property integer $company_id
 * @property integer $user_id
 * @property integer $event_id
 * @property string $start
 * @property string $end
 * @property integer $discount_money
 * @property integer $money
 * @property string $type
 */
class Visits extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'visits';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'company_id'], 'required'],
			[['user_id', 'smena_id', 'company_id', 'event_id', 'money', 'discount_money', 'type'], 'integer'],
			[['start', 'end'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'smena_id' => 'Смена',
			'company_id' => 'ID компании',
			'user_id' => 'ID гостя',
			'event_id' => 'Событие',
			'start' => 'Начало',
			'end' => 'Окончание',
			'discount_money' => 'Скидка',
			'money' => 'Сумма',
			'type' => '1 - Наличка, 2 - Безнал, 4 - GoodRepublic'
		];
	}
	
	public function getPersons()
	{
		return $this->hasOne(Persons::className(), ['id' => 'user_id']);
	}
	
	public function getCompany()
	{
		return $this->hasOne(Companies::className(), ['id' => 'company_id']);
	}
	
	public function getEvents()
	{
		return $this->hasOne(Events::className(), ['id' => 'event_id']);
	}
}
