<?php

namespace app\components;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use app\models\Companies;
use app\models\Users;

class CompanyWidget extends Widget
{
    public function init(){
        parent::init();
        $user = Users::findOne(Yii::$app->user->id);
        if(!empty($user->company_active))
        {
            $company = Companies::findOne($user->company_active);
        } 
        if (!empty($company)) {
            if (!empty($company->brand)) {
                echo $company->brand.' ('.$company->id.')';
            }else{
                echo $company->name.' ('.$company->id.')';
            }
        }else{
            echo 'Выбрите&nbsp;кабинет';
        }
    }
}

// <a href="/" class="logo"><img src="/web/images/logo-mini.svg" style="width: 40px; color: #e4cd2a"></a>