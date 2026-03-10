<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "markets".
 *
 * @property int $id
 * @property string $title 
 * @property int $biblioevent_id
 * @property int $link_id
 * @property string $site
 * @property string $info
 */
class Inviting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'markets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id'], 'required'],
            [['info','title' ], 'string' ],
            [['biblioevent_id', 'link_id', ], 'integer' ],
        ];
    }
    public function attributeLabels()
    {
        return [
            
            'title' => 'title',
            'info' => 'info',
            'site' => 'site: d.com',
            'link_id' => 'link_id',
            'biblioevent_id' => 'biblioevent_id',
        ];
    }

}
