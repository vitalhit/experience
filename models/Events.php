<?php

namespace app\models;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $biblioevent_id
 * @property string $title
 * @property string $alias
 * @property string $url
 * @property integer $abiblioevent_id
 * @property integer $place_id
 * @property integer $city_id
 * @property string $city
 * @property string $place
 * @property string $address
 * @property string $artist
 * @property string $date
 * @property string $date_open
 * @property string $date_end
 * @property string $date_release
 * @property integer $no_date
 * @property integer $duty
 * @property string $zal
 * @property string $link_vk
 * @property string $link_tg
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_yt
 * @property string $link_vk_post
 * @property string $link_vk_cc
 * @property string $link_vk_repost
 * @property string $link_kg
 * @property string $link_buy
 * @property string $link_bot
 * @property string $link_gmap
 * @property string $video1
 * @property string $video2
 * @property string $video2
 * @property string $button
 * @property string $buttontext
 * @property string $buttontextdate
 * @property string $underbutton
 * @property string $formbutton
 * @property string $formtext
 * @property int $shema
 * @property int $maxseats
 * @property int $soldout
 * @property string $letter
 * @property string $field1
 * @property string $field2
 * @property string $field3
 * @property string $field4
 * @property string $field5
 * @property string $field6
 * @property string $field7
 * @property string $field8
 * @property string $field9
 * @property string $field10
 * @property string $phone_ask
 * @property string $secretcode_ask
 * @property string $status
 * @property integer $status_promo
 * @property string $info
 * @property string $info_draft
 * @property string $info_eventer
 * @property string $serv_info
 * @property string $text_ad_25
 * @property string $text_ad_90
 * @property string $text_ad_220
 * @property string $button_html
 * @property string $button_html_head
 * @property string $image
 * @property integer $report_seats
 * @property integer $cancel
 * @property integer $img_id
 * @property integer $deleted
 * @property integer $hide_in_band
 * @property integer $type
 */

class Events extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'events';
    }

    public function rules()
    {
        return [
            [['event_id', 'date'], 'required'],
            [['event_id','abiblioevent_id','biblioevent_id', 'img_id', 'place_id', 'city_id', 'status', 'status_promo', 'no_date', 'shema', 'soldout', 'maxseats', 'report_seats','cancel', 'deleted','hide_in_band','secretcode_ask','phone_ask', 'type','link_vk_id'], 'integer'],
            [['date'], 'safe'],
            [['info', 'title', 'info_draft', 'info_eventer', 'image','date_open','date_end','date_release', 'button', 'buttontext', 'buttontextdate', 'underbutton', 'formbutton','formtext', 'letter', 'city', 'place', 'address', 'artist', 'field1', 'field2', 'field3', 'field4', 'field5', 'field6', 'field7', 'field8', 'field9', 'field10','status', 'link_tg','link_vk', 'link_vk_cc', 'link_vk_post', 'link_vk_repost', 'link_fb','link_yt','link_insta','link_kg','link_buy','link_bot', 'link_gmap','video1','video2','video3', 'button_html', 'button_html_head', 'serv_info','text_ad_25','text_ad_90','text_ad_220', 'alias', 'url'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Событие',
            'abiblioevent_id' => 'Отображать в biblioevent с id:',
            'no_date' => 'Без даты: 1 - без даты',
            'place_id' => 'Место из каталога',
            'ctiy' => 'Город',
            'place' => 'Название места',
            'address' => 'Адрес и как добраться',
            'artist' => 'Артисты',
            'date' => 'Дата',
            'date_open' => 'Дата открытия дверей',
            'button' => 'Надпись на кнопке',
            'buttontext' => 'Надпись на кнопке под датой',
            'buttontextdate' => 'Текст на кнопке вместо даты',
            'underbutton' => 'Надпись под кнопкой',
            'formbutton' => 'На-сь «на кнопке под формой»',
            'formtext' => 'Текст в форме',
            'shema' => 'Показать схему зала в форме',
            'letter' => 'Текст в письме для этой даты',
            'field1' => 'Название поля 1',
            'field2' => 'Название поля 2',
            'field3' => 'Название поля 3',
            'field4' => 'Название поля 4',
            'field5' => 'Название поля 5',
            'field6' => 'Название поля 6',
            'field7' => 'Название поля 7',
            'field8' => 'Название поля 8',
            'field9' => 'Название поля 9',
            'field10' => 'Название поля 10',
            'status' => 'Статус даты',
            'status_promo' => 'partner.igoevent.com',
            'info' => 'Текст про дату',
            'info_draft' => 'Текст-черновик про дату',
            'info_eventer' => 'Пометка  орг-ра ',
            'link_vk' => 'короткий url вк',
            'link_vk_cc' => 'vk.cc',
            'link_vk_id' => 'id встречи ВК',
            'link_vk_post' => 'vk:посев',
            'link_vk_repost' => 'vk:repost',
            'link_fb' => 'fb:link',
            'link_yt' => 'ссылка на канал youtube',
            'link_bot' => 'Ссылка на бота',
            'link_buy' => 'Ссылка/покупка(стороний сайт)',
            'link_insta' => 'instagram:link',
            'video1' => 'id video из youtube',
            'video2' => 'id video из youtube',
            'video3' => 'id video из youtube',
            'link_kg' => 'ссылка на kudago.com',
            'image' => 'Афиша',
            'deleted' => 'Удален'
        ];
    }

    public function getBiblioevents()
    {
        return $this->hasOne(Biblioevents::className(), ['id' => 'event_id']);
    }

    /**
     * Alias для getBiblioevents() — совместимость с BookingController
     */
    public function getBiblioevent()
    {
        return $this->getBiblioevents();
    }

    public function getSeats()
    {
        return $this->hasMany(Seats::className(), ['event_id' => 'id']);
    }

    public function getPlaces()
    {
        return $this->hasOne(Places::className(), ['id' => 'place_id']);
    }

    public static function getEvents($biblioevent_id = false)
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

    public static function getIds($c_ids)
    {
        $biblio = Biblioevents::find()->where(['company_id' => $c_ids])->all();
        $biblio_ids = ArrayHelper::getColumn($biblio, 'id');
        $eve = Events::find()->where(['event_id' => $biblio_ids])->all();
        return ArrayHelper::getColumn($eve, 'id');
    }

    public function getTickets()
    {
        return $this->hasMany(Tickets::className(), ['event_id' => 'id']);
    }

    public function getAlienevents()
    {
        return $this->hasMany(Events::className(), ['id' => 'event_id'])->viaTable('biblioevent_event', ['biblioevent_id' => 'id']);
    }
}