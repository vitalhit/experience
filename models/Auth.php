<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string $source_id
 *
 * @property User $user
 */
class Auth extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'auth';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user_id', 'source', 'source_id'], 'required'],
			[['user_id'], 'integer'],
			[['source', 'source_id'], 'string', 'max' => 255],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'source' => 'Source',
			'source_id' => 'Source ID',
		];
	}


	// Создадим Auth
	public function Add($uid, $type, $from_id)
	{
		if ($type == 1) { $source = 'vkontakte'; } elseif ($type == 2) { $source = 'telegram'; } elseif ($type == 3) { $source = 'facebook'; }

		$auth = Auth::find()->where(['user_id' => $uid, 'source' => $source, 'source_id' => $from_id])->one();
		if (empty($auth)) {
			$auth = new Auth();
			$auth->user_id = $uid;
			$auth->source = $source;
			$auth->source_id = "$from_id";
			if (!$auth->save()) {
				// Error::addError('models/Auth/Add', 'Не оздалось. Юзер '.$uid.' '.json_encode($auth->getErrors(), JSON_UNESCAPED_UNICODE));
				file_put_contents('test.txt', PHP_EOL.'АУС ОШИБКА: '.json_encode($auth->getErrors(), JSON_UNESCAPED_UNICODE), FILE_APPEND);

			}
		}
	}


	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
	
	public function getProfile()
	{
		return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
	}
}
