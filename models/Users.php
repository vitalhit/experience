<?php

namespace app\models;

use Yii;
use app\models\PasswordResetRequest;
use mdm\admin\models\Assignment;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $company_active
 * @property string $username
 * @property int $person_id
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $utm_medium
 * @property string $refresh_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Users extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
			[['company_active', 'person_id', 'status', 'created_at', 'updated_at'], 'integer'],
			[['username', 'auth_key','utm_medium'], 'string', 'max' => 32],
			[['password_hash', 'password_reset_token', 'email', 'email_confirm', 'refresh_token'], 'string', 'max' => 255],
		];
	}


	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'company_active' => 'ID Активной компании',
			'username' => 'Username',
			'person_id' => 'Person ID',
			'auth_key' => 'Auth Key',
			'password_hash' => 'Password Hash',
			'password_reset_token' => 'Password Reset Token',
			'email' => 'Email',
			'status' => 'Status',
			'email_confirm' => 'Токен ля подтверждений mail',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}



	// Создаем нового usera (личный кабинет)
	public static function createUser($person)
	{
		if (empty($person->mail)) { return; }
		$user = Users::find()->where(['email' => $person->mail])->andWhere(['>', 'status', 0])->orderBy('id')->one();
		if (!empty($user)) { return $user; }


		$user = new Users(); 
		$user->username = substr($person->mail, 0, 30);
		$user->email = $person->mail;
		//$user->utm_medium = substr($person->mail, 0, strripos($person->mail, "@"));
		$user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
		$user->auth_key = Yii::$app->security->generateRandomString();
		$user->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomString());
		$user->email_confirm = Yii::$app->security->generateRandomString();
		$user->created_at = $time = time();
		$user->updated_at = $time;
		$user->person_id = $person->id;

		if ($user->save()) {
			$userRole = Yii::$app->authManager->getRole('user');
			$role = Yii::$app->authManager->assign($userRole, $user->id);
			$person = Persons::findOne($person->id);
			$person->user_id = $user->id;
			$person->status = '1';
			if (!$person->save()) {
				// file_put_contents('test.txt', PHP_EOL.Date('d.m.Y H:i:s - ПЕРСОНА: ').$person->id.' '.json_encode($person->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
			}

			// if ($role) {
			// 	Yii::$app->mailer->compose(['html' => 'lkCreate-html', 'text' => 'lkCreate-text'], ['user' => $user])
			// 	->setFrom([Yii::$app->params['supportEmail'] => 'Igoevent.com'])
			// 	->setTo($user->email)
			// 	->setSubject('Спасибо за регистрацию на Igoevent.com')
			// 	->send();
			// }
			return $user;
		} else {
			file_put_contents('test.txt', PHP_EOL.Date('d.m.Y H:i:s - ОШИБКА: ').json_encode($user->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
		}
	}


	// Создаем нового юзера через соцсеть!!!!
	public static function addUser($username, $email = false, $name)
	{
		file_put_contents('test.txt', PHP_EOL . PHP_EOL . 'Эдд Юзер ', FILE_APPEND);
		file_put_contents('test.txt', PHP_EOL . json_encode($username,JSON_UNESCAPED_UNICODE), FILE_APPEND);
		file_put_contents('test.txt', PHP_EOL . json_encode($email,JSON_UNESCAPED_UNICODE), FILE_APPEND);
		file_put_contents('test.txt', PHP_EOL . json_encode($name,JSON_UNESCAPED_UNICODE), FILE_APPEND);

		if(!empty($email)) {
			$user = User::find()->where(['email' => $email])->one();
			if(!empty($user)) { return $user; }
		} else {
			$email = $username;
		}

		$user = new User(); 
		$user->username = $username;
		$user->email = $email;
		$user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
		$user->auth_key = Yii::$app->security->generateRandomString();
		$user->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomString());
		$user->status = 10;
		$user->created_at = $time = time();
		$user->updated_at = $time;
		if ($user->save()) {
			// company_id где взять
			$userRole = Yii::$app->authManager->getRole('user');
			$role = Yii::$app->authManager->assign($userRole, $user->id);
			$person = Persons::addPerson($user->id, $name, null, 1, $email, null);
			return $user;
		} else {
			file_put_contents('test.txt', PHP_EOL . 'ОШИБКА эдд юзер '. json_encode($user->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
		}
	}

	public function findByEmailConfirm($email_confirm)
	{
		return static::findOne(['email_confirm' => $email_confirm]);
	}

	public function removeEmailConfirm()
	{
		$this->email_confirm = null;
	}

	public function getPerson()
	{
		return $this->hasOne(Persons::className(), ['id' => 'person_id']);
	}

	// Имя и фамилия для select
	/// Пытаюсь, понять есть ли тут конфликт. /// delete comments in 13.11.2024 
	/// Non-static method app\models\Users::Map() cannot be called statically 
	/// crm/views/tasks/_form.php 72 
	public static function Map($users)
	{
		foreach ($users as $user) {
			$one = Users::find()->where(['user.id' => $user['user_id']])->joinWith('person')->one();
			if (!empty($one->person->name)) {
				$result[$one->id] = $one->person->name.' '.$one->person->second_name;
			}
		}
		return $result;
	}

	public static function findIdentityByAccessToken($token, $type = null)
	{
	    try {
	        $data = Yii::$app->jwt->decode($token);
	    } catch (\Throwable $e) {
	        return null;
	    }

	    return static::findOne($data['id']);
	}

	/**
     * Validates password
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * Generates password hash
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateRefreshToken()
    {
        $this->refresh_token = Yii::$app->security->generateRandomString(128);
        return $this->save() ? $this->refresh_token : false;
    }

    public function validateRefreshToken($token)
    {
        return $this->refresh_token === $token;
    }

    public function invalidateRefreshToken()
    {
        $this->refresh_token = null;
        return $this->save();
    }

}
