<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newsmaker_section".
 *
 * @property int $id
 * @property int $newsmaker_id
 * @property int $section_id
 * @property inn $deleted
 */
class BiblioeventSection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsmaker_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['newsmaker_id', 'section_id'], 'required'],
            [['newsmaker_id', 'section_id', 'deleted'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'newsmaker_id' => 'Newsmaker ID',
            'section_id' => 'Section ID',
            'deleted' =>'Удален'
        ];
    }

    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }
}
