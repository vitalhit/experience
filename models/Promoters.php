<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promoters".
 *
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property string $name
 * @property string $second_name
 * @property string $middle_name
 * @property string $mail
 * @property string $phone
 * @property string $status
 * @property string $groups
 * @property int $sex
 * @property string $birthday
 * @property string $city
 * @property string $vishes
 * @property int $froms_id
 * @property int $sendmail
 * @property string $info
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property string $site
 * @property int $vk_id
 * @property int $fb_id
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_insta
 */
class Promoters extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promoters';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'sex', 'froms_id', 'sendmail', 'vk_id', 'fb_id'], 'integer'],
            [['company_id', 'name', 'second_name'], 'required'],
            [['birthday', 'lastvisit'], 'safe'],
            [['vishes', 'info', 'site','utm_source','utm_medium','utm_campaign','utm_content','utm_term','link_insta','link_vk','link_fb'], 'string'],
            [['name', 'second_name', 'middle_name', 'mail', 'phone', 'status', 'groups', 'city', 'link_insta','link_vk','link_fb'], 'string', 'max' => 255],
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
            'second_name' => 'Second Name',
            'middle_name' => 'Middle Name',
            'mail' => 'Mail',
            'phone' => 'Phone',
            'status' => 'Status',
            'groups' => 'Groups',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'city' => 'City',
            'vishes' => 'Vishes',
            'froms_id' => 'Froms ID',
            'sendmail' => 'Sendmail',
            'info' => 'Info',
            'utm_source' => 'utm_source',
            'utm_medium' => 'utm_medium',
            'utm_campaign' => 'utm_campaign',
            'utm_content' => 'utm_content',
            'utm_term' => 'utm_term',
            'site' => 'Site(без http://)',
            'vk_id' => 'Vk ID',
            'fb_id' => 'Fb ID',
            'link_insta' => 'Instagram Url',
            'link_vk' => 'VKontakte Url',
            'link_fb' => 'Facebook Url',
        ];
    }
}
