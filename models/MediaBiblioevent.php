<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media_biblioevent".
 *
 * @property int $id
 * @property int $media_id
 * @property int $biblioevent_id
 * @property inn $link
 */
class MediaBiblioevent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media_biblioevent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['newsmaker_id', 'section_id'], 'required'],
            [['newsmaker_id', 'section_id', 'link'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'media_id' => 'Media ID',
            'biblioevent_id' => 'Biblioevent ID',
            'link' =>'link'
        ];
    }

    public function getSection()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }
}
