<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brands".
 *
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property int $company_id
 * @property string $name
 * @property string $second_name
 * @property string $middle_name
 * @property string $mail
 * @property string $phone
 * @property int $discount
 * @property string $status
 * @property string $groups
 * @property int $sex
 * @property string $date_create
 * @property string $city_id
 * @property string $vishes
 * @property int $froms_id
 * @property int $sendmail
 * @property string $info
 * @property string $serv_info
 * @property int $template_id
 * @property string $link_site
 * @property int $vk_id
 * @property int $public_vk_id
 * @property int $fb_id
 * @property string $link_tg
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

class Brands extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brands';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'discount', 'sex', 'froms_id', 'vk_id','public_vk_id', 'fb_id','template_id', 'deleted'], 'integer'],
            [['company_id', 'name', 'second_name'], 'required'],
            [['date_create', 'lastvisit'], 'safe'],
            [['vishes', 'info', 'serv_info', 'link_site','title'], 'string'],
            [['name', 'second_name', 'middle_name', 'mail', 'phone', 'status', 'groups', 'city_id', 'link_insta','link_tt','link_tg','link_vk','link_ok','subject','utm_term','utm_content','utm_campaign','utm_medium','utm_source'], 'string', 'max' => 255],
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
            'discount' => 'Discount',
            'status' => 'Status',
            'groups' => 'Groups',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'city_id' => 'City',
            'vishes' => 'Vishes',
            'froms_id' => 'Froms ID',
            'sendmail' => 'Sendmail',
            'info' => 'Info',
            'template_id' => 'template_id',
            'link_site' => 'Site(without https://)',
            'vk_id' => 'Vk ID',
            'fb_id' => 'Fb ID',
            'link_insta' => 'link insta',
            'link_tt' => 'TikTok Url',
            'link_vk' => 'link_vk',
            'link_ok' => 'link_ok',

        ];
    }

        public function getTicketersevents()
    {
        return $this->hasMany(TicketersEvents::className(), ['event_id' => 'id']);

    }

    public function getPosts()
    {
        return $this->hasMany(Posts::class, ['item_id' => 'id'])
        ->andWhere(['item' => 'brand']);
    }

    public function getPost()
    {
        return $this->hasOne(Posts::class, ['item_id' => 'id'])
        ->andWhere(['item' => 'brand']);
    }

    public function getImgs()
    {
        return $this->hasMany(Img::class, ['id' => 'img_id'])
            ->viaTable('post_img', ['post_id' => 'id']);
    }

}
