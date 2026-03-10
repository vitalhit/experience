<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "public_vk".
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $company_id
 * @property string $name
 * @property string $public_name
 * @property string $topic_out
 * @property string $topic_vk
 * @property string $pablic_name
 * @property string $phone
 * @property int $public_id
 * @property string $status
 * @property string $type
 * @property string $city_id
 * @property int $froms_id
 * @property int $sendmail
 * @property string $info
 * @property int $template_id
 * @property int $inside
 * @property string $link_site
 * @property int $public_vk_id
 * @property string $link_insta
 * @property string $link_tt
 * @property string $link_vk
 * @property string $link_ok
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property string $body
 * @property string $subject
 * @property int $deleted
 */
class Publicvk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'public_vk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'discount', 'sex', 'froms_id', 'sendmail', 'inside', 'sum_visits', 'sum_tickets', 'vk_id','public_vk_id', 'fb_id','template_id', 'city_id'], 'integer'],
            [['company_id', 'name', 'second_name'], 'required'],
            [['birthday', 'lastvisit'], 'safe'],
            [['vishes', 'info', 'site','title','topic_vk','topic_our'], 'string'],
            [['name', 'second_name', 'middle_name', 'mail', 'phone', 'status', 'groups', 'city', 'link_insta','link_tt','link_vk','link_ok','subject','body','utm_term','utm_content','utm_campaign','utm_medium','utm_source'], 'string', 'max' => 255],
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
            'company_id' => 'Company ID',
            'name' => 'Name',
            'topic_our' => 'topic_our',
            'topic_vk' => 'topic_vk',
            'mail' => 'Mail',
            'phone' => 'Phone',
            'discount' => 'Discount',
            'status' => 'Status',
            'groups' => 'Groups',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'city' => 'City',
            'vishes' => 'Vishes',
            'froms_id' => 'Froms ID',
            'sendmail' => 'Sendmail',
            'info' => 'Info',
            'template_id' => 'template_id',
            'inside' => 'Inside',
            'lastvisit' => 'Lastvisit',
            'sum_visits' => 'Sum Visits',
            'sum_tickets' => 'Sum Tickets',
            'site' => 'Site(without https://)',
            'vk_id' => 'Vk ID',
            'fb_id' => 'Fb ID',
            'link_insta' => 'Instagram Url',
            'link_tt' => 'TikTok Url',
            'link_vk' => 'link_vk',
            'link_ok' => 'link_ok',

        ];
    }

        public function getNewsmakersevents()
    {
        return $this->hasMany(NewsmakersEvents::className(), ['event_id' => 'id']);

    }
}
