<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_place".
 *
 * @property int $id
 * @property int $post_id
 * @property int $biblioevent_id
 * @property int $task_id
 * @property int $type
 * @property string $post_vk
 * @property string $post_vk_link
 * @property string $music
 */
class PostBiblioevent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_biblioevent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['biblioevent_id'], 'required'],
            [['post_id','biblioevent_id','task_id','type'], 'integer'],
            [['post_id', 'post_vk_link','music'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'place_id' => 'Place ID',
        ];
    }

    public function getPlace()
    {
        return $this->hasOne(Places::className(), ['id' => 'place_id']);
    }
}
