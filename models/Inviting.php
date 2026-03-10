<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property int $public_vk_id 
 * @property int $public_id
 * @property int $event_id
 * @property int $event_vk_id
 * @property int $result_all
 * @property int $result_invite
 * @property int $result_ignore
 * @property int $result_ban
 * @property string $info
 */
class Inviting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inviting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id'], 'required'],
            [['info' ], 'string' ],
            [['public_id','event_id','public_vk_id','event_vk_id','result_all','result_invite','result_ignore','result_ban', ], 'integer' ],
        ];
    }
    public function attributeLabels()
    {
        return [
            
            'result_all' => 'All',
            'result_invite' => 'Invite',
            'result_ignore' => 'Ignore',
            'result_ban' => 'Ban',
        ];
    }

}
