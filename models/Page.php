<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $alias
 * @property string $name
 * @property string $anons
 * @property string $text
 * @property string $image
 * @property string $ogtitle
 * @property string $ogdescription
 * @property string $ogimage
 * @property string $seotitle
 * @property string $seokey
 * @property string $seodesc
 * @property string $create_at
 * @property int $status
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'user_id'], 'required'],
            [['text'], 'string'],
            [['status', 'category_id', 'user_id'], 'integer'],
            [['create_at'], 'safe'],
            [['name', 'anons', 'ogtitle', 'ogdescription', 'ogimage', 'seotitle', 'seokey', 'seodesc', 'alias', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'ID Категории',
            'alias' => 'Алиас (url)',
            'name' => 'Название',
            'anons' => 'Анонс',
            'text' => 'Текст',
            'image' => 'Картинка',
            'ogtitle' => 'Ogtitle',
            'ogdescription' => 'Ogdescription',
            'ogimage' => 'Ogimage',
            'seotitle' => 'Seo title',
            'seokey' => 'Seo key',
            'seodesc' => 'Seo desc',
            'status' => 'Статус',
            'create_at' => 'Create At',
        ];
    }
}
