<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "biblioevents".
 *
 * @property integer $id
 * @property integer $band_id
 * @property integer $company_id
 * @property integer $landing_id
 * @property integer $template_id
 * @property string $landing_type
 * @property integer $place_id
 * @property string $place_comment
 * @property string $vksend
 * @property string $name
 * @property string $subtitle
 * @property integer $status
 * @property string $alias
 * @property integer $city
 * @property string $image
 * @property string $age
 * @property integer $category_id
 * @property integer $seating_id
 * @property string $url
 * @property string $phone
 * @property string $phone2
 * @property string $link_vk
 * @property string $link_vk_img
 * @property string $link_vk_audio
 * @property string $link_vk_cc
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_kg
 * @property string $link_bot
 * @property string $link_buy
 * @property intager $redirect_time
 * @property string $button_default
 * @property string $button_head
 * @property string $button_code
 * @property string $button_link
 * @property string $button_text
 * @property intager $show_date
 * @property string $counter_head
 * @property string $counter_body
 * @property string $info
 * @property string $info_reg_after
 * @property string $info_mail
 * @property string $serv_info
 * @property string $a_company
 * @property string $a_p_city
 * @property string $a_p_place
 * @property string $a_p_sec
 * @property string $a_p_bib
 * @property integer $a_priority
 * @property integer $a_all
 * @property string $a_m_city
 * @property string $a_m_place
 * @property string $a_m_sec
 * @property string $a_m_bib
 * @property string $a_start
 * @property string $a_end
 * @property integer $click
 * @property integer $event_status
 * @property integer $deleted


 */
class Biblioevents extends \yii\db\ActiveRecord
{


	public static function tableName()
	{
		return 'biblioevents';
	}


	public function rules()
	{
		return [
			[['name', 'place_id'], 'required'],
			[['category_id', 'band_id', 'company_id', 'place_id', 'city', 'landing_id', 'template_id', 'seating_id', 'status', 'a_priority', 'a_all', 'click', 'deleted','status','event_status','show_date', 'redirect_time'], 'integer'],
			[['info','info_reg_after','info_mail', 'serv_info', 'button_head', 'button_code', 'button_link', 'button_default', 'button_text', 'vksend', 'place_comment','link_vk_audio', 'counter_body', 'counter_head','landing_type'], 'string'],
			[['name', 'alias', 'subtitle', 'url', 'phone', 'phone2', 'link_buy', 'link_vk', 'link_vk_img', 'link_vk_cc', 'link_fb', 'link_insta', 'link_kg', 'link_bot', 'image', 'age', 'a_company', 'a_p_bib', 'a_p_city', 'a_p_place', 'a_p_sec', 'a_m_bib', 'a_m_city', 'a_m_place', 'a_m_sec'], 'string', 'max' => 255],
			[['a_start', 'a_end'], 'safe'],
		];
	}

	
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'company_id' => 'Компания',
			'place_id' => 'Место (Если вы хотите добавить место, пишите на v@igoevent.com)',
			'place_comment' => 'Комментарий организатора про место проведения',
			'landing_id' => 'ID лендинга',
			'template_id' => 'ID шаблона',
			'vksend' => 'id вк через запятую для уведомлений',
			'name' => 'Название события',
			'band_id' => 'id хэд лайнера',
			'status' => 'Статус',
			'alias' => 'Алиас (url)',
			'city' => 'Город',
			'url' => 'Ссылка',
			'image' => 'Картинка',
			'age' => 'Возрастное ограничение',
			'category_id' => 'Категория события',
			'seating_id' => 'Рассадка',
			'info' => 'Информация для пресс-релиза',
			'serv_info' => 'служебная информация(для админа)',
			'info_reg_after' => 'после регистрации/покупки',
			'phone' => 'телефон',
			'phone2' => 'телефон для брони столиков',
			'vk' => 'vk id для связи',
			'link_vk' => 'link_vk: vitalhit',
			'link_vk_img' => 'ссылка на фото из вк',
			'link_vk_audio' => 'link_vk_audio',
			'link_vk_cc' => 'vk.cc/bYHtxa',
			'link_bot' => 'ссылка на бота поле рег.',
			'redirect_time' => 'Время редиректа',
			'fb' => 'facebook',
			'button_default' => 'Текст на кнопке по умолчанию',
			'button_head' => 'Код в <head>',
			'button_code' => 'Код кнопки в <body> ',
			'button_link' => 'Ссылка кнопки',
			'show_date' => 'Показывать дату',
			'counter_head' => 'Код счетчика в <head>',
			'counter_body' => 'Код счетчика в <body>',
			'insta' => 'instagram',
			'status' => 'Статус',
			'a_company' => 'ID Кабинетов',
			'a_p_city' => 'ID Городов',
			'a_p_place' => 'ID Мест',
			'a_p_sec' => 'ID Разделов',
			'a_p_bib' => 'ID Событий',
			'a_m_city' => 'Кроме ID Городов',
			'a_m_place' => 'Кроме ID Мест',
			'a_m_sec' => 'Кроме ID Разделов',
			'a_m_bib' => 'Кроме ID Событий',
			'a_start' => 'Начало',
			'a_end' => 'Окончание',
			'a_priority' => 'Вес: больше = выше',
			'a_all' => 'Все',
			'click' => 'Сколько кликов по баннеру',
			'deleted' => 'Удален: 0 - нет, 1 - да'
		];
	}


	public static function EventOwner($alias, $city)
	{
		$ids = Companies::getIds();

		if (empty($ids)) {
			return null;
		}

        // echo "<pre>";
		// print_r($ids);
		// echo "</pre>";die;

        // $biblioevents = Biblioevents::find()->where(['company_id' => $ids])->all(); Виталик сохранил себе
		$ci = Cities::find()->where(['alias' => $city])->one();

		if (empty($ci)) {
			return null;
		}

		if(!is_numeric($alias)) {
			$biblioevent = Biblioevents::find()->where(['company_id' => $ids, 'alias' => $alias, 'city' => $ci->id])->one();
		} else {
			$biblioevent = Biblioevents::find()->where(['company_id' => $ids, 'id' => $alias, 'city' => $ci->id])->one();
		}

		if (!empty($biblioevent)) {
			return $biblioevent;
		}
	}

	public static function EventsIdsAll($company = false)
	{
		$event_ids = Null;
		if (!empty($company)) {
			$biblioevents = Biblioevents::find()->where(['company_id' => $company->id])->joinwith('events')->asArray()->all();
		} else {
			$biblioevents = Biblioevents::find()->where(['company_id' => Companies::getCompanyId()])->joinwith('events')->asArray()->all();	
		}
		if (!empty($biblioevents)) {
			foreach ($biblioevents as $biblioevent) {
				foreach ($biblioevent['events'] as $event) {
					$event_ids[] = ArrayHelper::getValue($event, 'id');
				}
			}
			return $event_ids;
		}
	}


	public static function EventsIds($biblioevent)
	{
		$biblioevent = Biblioevents::find()->where(['biblioevents.id' => $biblioevent->id])->joinwith('events')->one();
		foreach ($biblioevent->events as $event) {
			$event_ids[] = $event->id;
		}
		return $event_ids??Null;
	}


	public function getLanding()
	{
		return $this->hasOne(Landing::className(), ['id' => 'landing_id']);
	}
	
	public function getCategoryevents()
	{
		return $this->hasOne(Categoryevents::className(), ['id' => 'category_id']);
	}
	
	public function getPlaces()
	{
		return $this->hasOne(Places::className(), ['id' => 'place_id']);
	}
	
	public function getCompany()
	{
		return $this->hasOne(Companies::className(), ['id' => 'company_id']);
	}
	
	public function getCities()
	{
		return $this->hasOne(Cities::className(), ['id' => 'city']);
	}
	
	public function getImg()
	{
		return $this->hasOne(Img::className(), ['image' => 'image']);
	}

	public function getEvents()
	{
		return $this->hasMany(Events::className(), ['event_id' => 'id'])->orderBy('events.date');
	}

	public function getOneactiveevent()
	{
		return $this->hasOne(Events::className(), ['event_id' => 'id'])->andWhere('DATE(date) >= DATE(NOW())')->andWhere(['events.status' => 1])->orderBy('date');
	}

	public function getActiveevents()
	{
		return $this->hasMany(Events::className(), ['event_id' => 'id'])->andWhere('DATE(date) >= DATE(NOW())')->andWhere(['events.status' => 1])->orderBy('date');
	}

	public function getletterBuy()
	{
		return $this->hasOne(Letters::className(), ['biblioevent_id' => 'id']);
	}

	public function getBiblioeventSection()
	{
		return $this->hasMany(BiblioeventSection::className(), ['biblioevent_id' => 'id']);
	}

	public function getSectionone()
	{
		return $this->hasOne(Section::className(), ['id' => 'section_id'])->viaTable('biblioevent_section', ['biblioevent_id' => 'id']);
	}

	public function getSections()
	{
		return $this->hasMany(Section::className(), ['id' => 'section_id'])->viaTable('biblioevent_section', ['biblioevent_id' => 'id']);
	}

	public function getPosts()
	{
		return $this->hasMany(Posts::className(), ['id' => 'post_id'])->viaTable('post_biblioevent', ['biblioevent_id' => 'id']);
	}


}
