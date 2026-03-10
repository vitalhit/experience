<?php

namespace app\models;

use Yii;
use app\models\Auth;
use app\models\User;
use app\models\Users;
use app\models\Profile;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{

	/**
	 * @var ClientInterface
	 */
	private $client;

	public function __construct(ClientInterface $client)
	{
		$this->client = $client;
	}

	public function handle()
	{
		//file_put_contents('test.txt', PHP_EOL . PHP_EOL . Date('H:i:s АУС'), FILE_APPEND);

		if (!Yii::$app->user->isGuest) { return; }

		$attributes = $this->client->getUserAttributes();
		//file_put_contents('test.txt', PHP_EOL . PHP_EOL . json_encode($attributes, JSON_UNESCAPED_UNICODE), FILE_APPEND);

		$auth = $this->findAuth($attributes);
		if ($auth) {
			//file_put_contents('test.txt', PHP_EOL . 'юзер: ' . json_encode($auth->user_id, JSON_UNESCAPED_UNICODE), FILE_APPEND);
			$user = $auth->user;
			$login = Yii::$app->user->login($user);
			Yii::$app->getUser()->setReturnUrl('/profile');
			return $login;
		}
		if ($user = $this->createAccount($attributes)) {
			$login = Yii::$app->user->login($user);
			Yii::$app->getUser()->setReturnUrl('/profile');
			return $login;              
		}
	}

	/**
	 * @param array $attributes
	 * @return Auth
	 */
	private function findAuth($attributes)
	{
		$id = ArrayHelper::getValue($attributes, 'id');
		$params = [
			'source_id' => $id,
			'source' => $this->client->getId(),
		];
		return Auth::find()->where($params)->one();
	}

	/**
	 * 
	 * @param type $attributes
	 * @return User|null
	 */
	private function createAccount($attributes)
	{
		file_put_contents('test.txt', PHP_EOL . PHP_EOL . 'креэйт: ' . json_encode($attributes, JSON_UNESCAPED_UNICODE), FILE_APPEND);
		$email = ArrayHelper::getValue($attributes, 'email');
		$id = ArrayHelper::getValue($attributes, 'id');
		$name = ArrayHelper::getValue($attributes, 'name');
		if (empty($name)) { $name = ArrayHelper::getValue($attributes, 'first_name') .' '. ArrayHelper::getValue($attributes, 'last_name'); }

		// if ($email !== null && User::find()->where(['email' => $email])->exists()) {
		// 	return;
		// }

		$user = Users::addUser($id, $email, $name);

		$transaction = User::getDb()->beginTransaction();
		if ($user->save()) {
			$auth = $this->createAuth($user->id, $id);
			if ($auth->save()) {
				$transaction->commit();
				return $user;
			}
		}
		$transaction->rollBack();
	}

	// private function addUser($email, $name)
	// {
	//     return new User([
	//         'username' => $name,
	//         'email' => $email,
	//         'auth_key' => Yii::$app->security->generateRandomString(),
	//         'password_hash' => Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomString()),
	//         'created_at' => $time = time(),
	//         'updated_at' => $time,
	//     ]);
	// }

	private function createAuth($userId, $sourceId)
	{
		return new Auth([
			'user_id' => $userId,
			'source' => $this->client->getId(),
			'source_id' => (string) $sourceId,
		]);
	}

}
