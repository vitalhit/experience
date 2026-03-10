<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_place".
 *
 * @property int $id
 * @property int $post_id
 * @property int $place_id
 */
class PostPlace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_place';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'place_id'], 'required'],
            [['post_id', 'place_id'], 'integer'],
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
