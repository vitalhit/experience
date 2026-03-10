<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "biblioevent_guide".
 *
 * @property int $biblioevent_id
 * @property int $guide_id
 * @property int $is_main
 * @property int $sort_order
 *
 * @property Biblioevents $biblioevent
 * @property Guide $guide
 */
class BiblioeventGuide extends ActiveRecord
{
    public static function tableName()
    {
        return 'biblioevent_guide';
    }

    public function rules()
    {
        return [
            [['biblioevent_id', 'guide_id'], 'required'],
            [['biblioevent_id', 'guide_id', 'is_main', 'sort_order'], 'integer'],
            [['biblioevent_id', 'guide_id'], 'unique', 'targetAttribute' => ['biblioevent_id', 'guide_id']],
        ];
    }

    public function getBiblioevent()
    {
        return $this->hasOne(Biblioevents::className(), ['id' => 'biblioevent_id']);
    }

    public function getGuide()
    {
        return $this->hasOne(Guide::className(), ['id' => 'guide_id']);
    }
}
