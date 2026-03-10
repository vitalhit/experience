<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $biblioevent_id
 * @property int $event_id
 * @property int $place_id
 * @property int $band_id
 * @property string $link
 * @property string $short
 * @property string $name
 * @property string $vk
 * @property string $fb
 * @property string $insta
 * @property string $tg
 * @property string $yt
 * @property string $tw
 * @property int $tool_id
 * @property int $media_id
 * @property int $newsmaker_id
 */
class Links extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['name','link','vk','fb','insta','yt','tw', 'short'], 'string'],
            [['biblioevent_id','event_id', 'band_id', 'place_id','tool_id','media_id','newsmaker_id'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'vk' => 'vk',
            'fb' => 'fb',
        ];
    }
    
}
