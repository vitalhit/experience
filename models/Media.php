<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string $title
 * @property string $web
 * @property string $email
 * @property string $info
 * @property string $city_id
 * @property string $serv_info
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_tg
 * @property string $public_vk_id
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'public_vk_id'], 'integer'],
            [['serv_info','info'], 'string'],
            [['title', 'web', 'email', 'link_vk', 'link_fb', 'link_insta','link_tg'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'web' => 'Web',
            'email' => 'email',
            'info' => 'Info',
            'city_id' => 'City Id',
            'serv_info' => 'serv_info',
            'link_vk' => 'link_vk',
            'link_fb' => 'link_fb',
            'link_insta' => 'link_insta',
            'link_tg' => 'link_tg',
            'public_vk_id' => 'public_vk_id',
            
        ];
    }

   
}
