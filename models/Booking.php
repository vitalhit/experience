<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "booking".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property int $company_id
 * @property int $city
 * @property int $biblioevent_id
 * @property int $event_id
 * @property string $event_id_text
 * @property int $event_finance_id
 * @property int $list_id
 * @property int $person_id
 * @property string $from_url
 * @property string $name
 * @property string $second_name
 * @property string $thirdname
 * @property int $owner_id
 * @property int $status_id
 * @property string $vk_id
 * @property string $fb_id
 * @property string $in_id
 * @property string $foto
 * @property string $birthday
 * @property string $mail
 * @property string $phone
 * @property int $brand
 * @property string $brand_id
 * @property string $link_site
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_photo
 * @property string $link_tg
 * @property string $message
 * @property int $sex
 * @property string $sale
 * @property string $time
 * @property int $task_id
 * @property int $project_id
 * @property string $close_time
 * @property int $role
 * @property string $result
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property string $info
 * @property string $info_wish
 * @property string $info_goal
 * @property string $info_job
 * @property string $info_cat
 * @property int $subscribe
 * @property string $serv_info
 * @property string $image
 * @property string $file
 * @property string $domain
 * @property int $price
 */
class Booking extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */

	public $file;

	public static function tableName()
	{
		return 'booking';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'company_id', 'status_id', 'mail'], 'required'],
			[['company_id', 'biblioevent_id','event_finance_id', 'event_id', 'list_id', 'person_id', 'subscribe', 'owner_id', 'status_id', 'sex', 'role', 'task_id', 'project_id', 'price', 'city', 'brand_id'], 'integer'],
			[['message', 'result', 'info','info_wish','info_goal','info_job','serv_info', 'info_cat','event_id_text', 'domain'], 'string'],
			[['time', 'close_time'], 'safe'],
			[['image', 'image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8', 'image9', 'image10', 'file'], 'file'],
			[['title','alias','from_url', 'name', 'second_name', 'thirdname', 'vk_id', 'fb_id', 'in_id', 'foto', 'birthday', 'mail', 'phone', 'sale', 'result', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'brand','link_site','link_photo','link_vk','link_fb','link_insta', 'link_tg'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'person_id' => 'Персона',
			'company_id' => 'ID компании',
			'biblioevent_id' => 'Событие',
			'list_id' => 'Лист ожидания',
			'from_url' => 'Откуда заявка',
			'name' => 'Имя',
			'second_name' => 'Фамилия',
			'thirdname' => 'Отчество',
			'owner_id' => 'Ответственный',
			'status_id' => 'Статус',
			'vk_id' => 'Vk ID',
			'fb_id' => 'Fb ID',
			'in_id' => 'In ID',
			'foto' => 'Фото',
			'birthday' => 'День рождения',
			'mail' => 'Mail',
			'phone' => 'Телефон',
			'message' => 'Сообщение/message',
			'sex' => 'Пол',
			'sale' => 'Скидка',
			'time' => 'Время регистрации',
			'close_time' => 'Время закрытия',
			'link_photo' => 'Ссылка на фотографии',
			'role' => 'Роль',
			'result' => 'Результат/result',
			'utm_source' => 'Utm Source',
			'utm_medium' => 'Utm Medium',
			'utm_campaign' => 'Utm Campaign',
			'utm_content' => 'Utm Content',
			'utm_term' => 'Utm Term',
			'info' => 'Информация/info',
			'info_wish' => 'Ваши ожидания/пожелания?',
			'info_goal' => 'К какой цели вы хотите прийти в конце обучения?',
			'info_job' => 'Род деятельности , чем вы занимаетесь?',
			'info_cat' => 'Категория вашего товара',
			'subscribe' => 'Подписан на новости',
			'serv_info' => 'Служебная информация',
			'image' => 'image',
		];
	}


	public function getOwners()
	{
		return $this->hasOne(Users::className(), ['id' => 'owner_id']);
	}
	
	public function getStatus()
	{
		return $this->hasOne(TasksStatus::className(), ['id' => 'status_id']);
	}
	

}
