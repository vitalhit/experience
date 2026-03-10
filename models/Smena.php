<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smena".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $start
 * @property string $end
 * @property string $time
 * @property int $type
 * @property int $visits_n
 * @property int $visits_b
 * @property int $tickets_n
 * @property int $tickets_b
 * @property int $sells_n
 * @property int $sells_b
 * @property int $abonements_n
 * @property int $abonements_b
 * @property int $rents_n
 * @property int $rents_b
 */
class Smena extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smena';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type'], 'required'],
            [['user_id', 'type', 'visits_n', 'visits_b', 'tickets_n', 'tickets_b', 'sells_n', 'sells_b', 'abonements_n', 'abonements_b', 'rents_n', 'rents_b'], 'integer'],
            [['start', 'end', 'time'], 'safe'],
            [['title'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        'id' => 'ID',
        'user_id' => 'User ID',
        'start' => 'Start',
        'end' => 'End',
        'time' => 'Time',
        'type' => 'Type',
        'visits_n' => 'Type',
        'visits_b' => 'Type',
        'tickets_n' => 'Type',
        'tickets_b' => 'Type',
        'sells_n' => 'Type',
        'sells_b' => 'Type',
        'abonements_n' => 'Type',
        'abonements_b' => 'Type',
        'rents_n' => 'Type',
        'rents_b' => 'Type'
        ];
    }
    
    public function findSmena() 
    {
        // Получаем id текущего администратора
        $admin_uid = Yii::$app->user->id;

        // Берем последнюю смену этого администратора
        $smena = Smena::find()->where(['user_id' => $admin_uid])->andWhere(['end' => NULL])->one();

        // Проверяем что она есть и что она открыта
        if ( is_null($smena->end) ) {
            return $smena;
        }
    }




    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
