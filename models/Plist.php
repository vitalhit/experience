<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "quote".
 *
 * @property int $id
 * @property int $public_vk_id
 * @property int $current_post
 * @property int $time
 * @property int $stop
 * @property string $name
 * @property string $goal
 * @property int $current_ptext
 * @property int $current_plink
 * @property int $current_paudio
 * @property int $current_pphoto
 * @property int $current_pvideo
 * @property int $current_pquote
 */
class Plist extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'plist';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['name','goal'], 'string'],
			[[ 'time','stop','current_post', 'public_vk_id','current_ptext','current_plink','current_paudio','current_pphoto','current_pvideo','current_pquote'], 'integer'],
			
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'goal' => 'Goal',
			'current_ptext' => 'current_ptextx',
		];
	}
 	
 	public function getBands()
    {
        return $this->hasMany(Bands::className(), ['id' => 'band_id'])->viaTable('band_plist', ['plist_id' => 'id']);
    }

    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['id' => 'post_id'])->viaTable('plist_post', ['plist_id' => 'id']);
    }

}
