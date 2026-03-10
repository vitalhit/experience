<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_img".
 *
 * @property int $id
 * @property int $post_id
 * @property int $img_id
 */
class PostImg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['img_id', 'post_id'], 'required'],
            [['post_id','img_id',], 'integer'],
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
            'img_id' => 'Img ID',
        ];
    }

}
