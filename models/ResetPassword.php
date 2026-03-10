<?php

namespace app\models;

use Yii;
use mdm\admin\models\form\ResetPassword as ResetPasswordForm;

class ResetPassword extends ResetPasswordForm
{
    public function rules()
    {
        return [
            [['password'], 'required'],
            ['password', 'string', 'min' => 6],
            ['retypePassword', 'compare', 'compareAttribute' => 'password']
        ];
    }
}
