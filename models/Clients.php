<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property string $name
 * @property string $second_name
 * @property string $middle_name
 * @property string $mail
 * @property string $mail_gmail
 * @property string $mail_yandex
 * @property string $phone
 * @property int $discount
 * @property string $status
 * @property string $groups
 * @property int $sex
 * @property string $birthday
 * @property string $city
 * @property string $vishes
 * @property int $froms_id
 * @property int $sendmail
 * @property string $info
 * @property int $inside
 * @property string $lastvisit
 * @property int $sum_visits
 * @property int $sum_tickets
 * @property int $vk_id
 * @property int $fb_id
 * @property string $link_insta
 * @property string $link_tt
 * @property string $link_tg
 * @property string $link_site
 * @property string $link_help
 * @property string $tags
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'discount', 'sex', 'froms_id', 'sendmail', 'inside', 'sum_visits', 'sum_tickets', 'vk_id', 'fb_id'], 'integer'],
            [['company_id', 'name',], 'required'],
            [['birthday', 'lastvisit'], 'safe'],
            [['vishes', 'info', 'link_site', 'tags'], 'string'],
            [['name', 'second_name', 'middle_name', 'mail', 'mail_gmail', 'mail_yandex', 'phone', 'status', 'groups', 'city', 'link_insta', 'link_tt', 'link_tg', 'link_help'], 'string', 'max' => 255],
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
            'city' => 'City',
            'vishes' => 'Vishes',
            'froms_id' => 'Froms ID',
            'sendmail' => 'Sendmail',
            'info' => 'Info',
            'inside' => 'Inside',
            'lastvisit' => 'Lastvisit',
            'sum_visits' => 'Sum Visits',
            'sum_tickets' => 'Sum Tickets',
            'site' => 'Site',
            'vk_id' => 'Vk ID',
            'fb_id' => 'Fb ID',
            'link_insta' => 'Instagram Url',
            'link_tt' => 'Тик Ток Url '
        ];
    }
}
