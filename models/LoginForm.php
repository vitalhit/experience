<?php

namespace app\models;

use Yii;
use mdm\admin\models\form\Login as LoginModel;

class LoginForm extends LoginModel
{

	public function attributeLabels()
	{
		return [
			'username' => 'Логин',
			'email' => 'Email',
			'password' => 'Пароль',
			'rememberMe' => 'Запомнить меня',
		];
    }

}
