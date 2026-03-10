<?php

namespace app\components;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\helpers\Html;
use app\models\Biblioevents;
use app\models\Companies;
use app\models\Users;


class EventOwnerWidget extends Widget
{
    public function init(){
        parent::init();
        $user = Users::findOne(Yii::$app->user->id);
        $ids = Companies::getIds();
        $biblioevents = Biblioevents::find()->where(['company_id' => $ids])->all();

        if (!empty($biblioevents)) {
            $bids = ArrayHelper::getColumn($biblioevents, 'id');
            echo $bids;
        }
    }
}
