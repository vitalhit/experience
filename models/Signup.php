<?php

namespace app\models;

use Yii;
use mdm\admin\models\form\Signup as SignupForm;

class Signup extends SignupForm
{

	public function rules()
	{
		$class = Yii::$app->getUser()->identityClass ? : 'mdm\admin\models\User';
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'unique', 'targetClass' => $class, 'message' => 'Этот логин занят.'],
			['username', 'string', 'min' => 2, 'max' => 255],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => $class, 'message' => 'Эот email занят. Возможно, вы покупали билет — вам нужно восстановить доступ.'],

			['password', 'required'],
			['password', 'string', 'min' => 6],
		];
	}

	
	public function attributeLabels()
	{
		return [
			'username' => 'Логин',
			'email' => 'Email',
			'password' => 'Придумайте пароль',
		];
	}

	public function signup()
	{
		if ($this->validate()) {
			$user = new User();
			// $user->username = $this->username;
			$user->username = $this->email;
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->generateAuthKey();
			if ($user->save()) {
				Signup::roleEventerPM($user->id);
				Yii::$app->getUser()->login($user);
				Yii::$app->getSession()->setFlash('success', 'Добро пожаловать в iGoEvent');
				return $user;
			}
		}

		return null;
	}


	// Назначаем Юзеру роль eventer и product-manager
	public function roleEventerPM($uid)
	{       
		$userRole = Yii::$app->authManager->getRole('eventer');
		$role = Yii::$app->authManager->assign($userRole, $uid);
		$userRole2 = Yii::$app->authManager->getRole('project-manager');
		$role = Yii::$app->authManager->assign($userRole2, $uid);
		return $role;
	}
}
