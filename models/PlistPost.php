<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "plist_post".
 *
 * @property int $id
 * @property int $post_id
 * @property int $plist_id
 * @property int $deleted
 * @property int $info
 */
class PlistPost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plist_post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plist_id', 'post_id'], 'required'],
            [['post_id', 'plist_id','deleted'], 'integer'],
            [['info'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'post ID',
            'plist_id' => 'plist ID',
        ];
    }

    public function getPosts()
    {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
    }

}
