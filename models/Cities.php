<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $info
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['info'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Город',
            'alias' => 'Алиас три латинские буквы',
            'info' => 'Алиас три латинские буквы',
        ];
    }

    public static function Active()
    {
        $bib = Biblioevents::find()->where('biblioevents.status = 1')
        ->joinWith(['events' => function ($query) {
            $query->where('DATE(events.date) >= DATE(NOW())');
        }])->asArray()->all(); // События только с активными датами
        $ids = array_unique(ArrayHelper::getColumn($bib, 'city'));
        $cities = Cities::find()->where(['id' => $ids])->all();
        return $cities;
    }




    public function getBiblioevents()
    {
        return $this->hasOne(Biblioevents::className(), ['id' => 'event_id']);
    }
}
