<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $item
 * @property integer $item_id
 * @property string $text
 * @property string $info
 * @property string $serv_info
 * @property string $usecase
 * @property string $html
 * @property string $tag 
 * @property string $link_photo
 * @property string $attach
 * @property string $link_audio
 * @property string $link_photos
 * @property string $link_videos
 * @property string $link_vk_cc
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link
 * @property integer $published
 * @property integer $deleted
 
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title','text','link','tag','attach','link_audio','link_photos','link_videos','link_vk_cc','link_vk','link','link_fb', 'item', 'info', 'serv_info', 'html', 'usecase'], 'string'],
            [['deleted','published', 'item_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'status',
            'info' => 'info',
            'title' => 'title',
        ];
    }

    public function getImgs()
    {
        return $this->hasMany(\app\models\Img::class, ['id' => 'img_id'])
            ->viaTable('post_img', ['post_id' => 'id']);
    }

    public function getPlace()
    {
        return $this->hasMany(Place::className(), ['id' => 'place_id'])->viaTable('post_place', ['post_id' => 'id']);
    }
    
}
