<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "froms".
 *
 * @property int $id
 * @property string $url
 * @property string $info
 */
class Froms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'froms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['info'], 'string'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'info' => 'Info',
        ];
    }
}
