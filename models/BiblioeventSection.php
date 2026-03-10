<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "biblioevent_section".
 *
 * @property int $id
 * @property int $biblioevent_id
 * @property int $section_id
 */
class BiblioeventSection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'biblioevent_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['biblioevent_id', 'section_id'], 'required'],
            [['biblioevent_id', 'section_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'biblioevent_id' => 'Biblioevent ID',
            'section_id' => 'Section ID',
        ];
    }

    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }
}
