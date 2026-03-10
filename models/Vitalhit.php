<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Vitalhit extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'vitalhit';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID'
		];
	}




	public function Pre($ar)
	{

		echo "<pre>";
        print_r($ar);              
        echo "</pre>";

        return 'finished';
	}








}
