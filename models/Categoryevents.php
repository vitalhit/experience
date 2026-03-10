<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoryevents".
 *
 * @property integer $id
 * @property string $category
 * @property string $title
 * @property string $company_id
 * @property string $category_en
 * @property string $type
 * @property integer $status
 */
class Categoryevents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoryevents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['category', 'company_id', 'category_en','type','title'], 'string', 'max' => 255],
            [['status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Название категории',
            'company_id' => 'Компания',
        ];
    }
}
