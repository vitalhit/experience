<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tickets_return".
 *
 * @property int $id
 * @property string $title
 * @property string $title_en
 * @property string $info
 * @property string $info_en
 * @property string $introtext
 * @property string $introtext_en
 * @property string $link_site
 * @property int $city_id
 * @property string $serv_info
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_tg
 * @property string $date_create
 * @property string $date_first
 * @property int $public_vk_id


 */

class Festival extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'festivals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            [['city_id',  'public_vk_id'], 'integer'],
            [['date_create','date_first'], 'safe'],
            [[ 'title', 'title_en', 'info_en', 'info', 'introtext', 'introtext_en','serv_info','link_site','link_tg','link_insta','link_fb','link_vk'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */ 
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'info' => 'Описание',
            'link_site' => 'Сайт без https://',
            'city_id' => 'id города',
            'serv_info' => 'Служебная информация',
            'link_vk' => 'link_vk',
            'link_fb()' => 'link_fb',
            'link_insta' => 'link_insta',
            'link_tg' => 'link_tg',
            'date_create' => 'Дата добавления',
            'public_vk_id' => 'public_vk_id'
        ];
    }
    

   public function getPersonf()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id'])->viaTable('user', ['id' => 'from_user_id']);
    }

    public function getPersonto()
    {
        return $this->hasOne(Persons::className(), ['id' => 'to_user_id']);
    }
    
    public function getContragent()
    {
        return $this->hasOne(Contragent::className(), ['id' => 'from_contragent']);
    }
    
    public function getContragentto()
    {
        return $this->hasOne(Contragent::className(), ['id' => 'to_contragent']);
    }
    
    public function getFestival($biblioevent_id = false)
    {
        if (!empty($biblioevent_id)) {
            $biblioevents = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->andWhere(['id' => $biblioevent_id])->asArray()->all();
        } else {
            $biblioevents = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->all();
        }
        $bib_ids = ArrayHelper::getColumn($biblioevents, 'id');
        $dates = Events::find()->where(['event_id' => $bib_ids])->joinWith('biblioevents')->orderBy(['date' => SORT_DESC])->all();
        return $dates;
    }
}
