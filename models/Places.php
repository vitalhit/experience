<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "places".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $landing_id
 * @property string $name
 * @property string $name_en
 * @property string $name_before
 * @property string $alias
 * @property string $city
 * @property integer $no_address
 * @property integer $status
 * @property integer $closed
 * @property integer $postcode
 * @property string $metro
 * @property string $phone
 * @property string $address
 * @property string $path
 * @property string $pathshort
 * @property string $description
 * @property string $yandex
 * @property string $map
 * @property string $foto
 * @property string $foto_stage
 * @property string $foto_street
 * @property string $foto_hall
 * @property string $foto_seats
 * @property string $shema
 * @property string $logo
 * @property string $menu
 * @property string $priority
 * @property string $site
 * @property string $info
 * @property string $info_event
 * @property string $info_ticket
 * @property string $info_for_event
 * @property string $info_dance_floor
 * @property string $serv_info
 * @property string $rider
 * @property integer $numseats
 * @property integer $numseats_max
 * @property integer $soldout
 * @property integer $numseats_vip
 * @property integer $numseats_dance_floor
 * @property integer $standing
 * @property integer $standing_max
 * @property integer $standing_building
 * @property integer $favour
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_yt
 * @property string $link_tg
 * @property string $link_ok
 * @property string $link_gmap
 * @property string $link_vk_map
 * @property string $link_rider
 * @property string $link_photo
 * @property string $video
 * @property string $video_map
 * @property string $video_car
 * @property string $css
 * @property string $scheme_width
 * @property integer $just_sitting
 */

class Places extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'city', 'address'], 'required'],
            [['company_id', 'landing_id', 'favour', 'no_address','status', 'closed', 'postcode', 'standing','standing_max','standing_building','numseats','numseats_max','numseats_vip','numseats_dance_floor','soldout', 'just_sitting'], 'integer'],
            [['path', 'description', 'rider', 'info', 'info_dance_floor','info_event', 'info_ticket', 'serv_info','css','scheme_width','info_for_eventer'], 'string'],
            [['name', 'name_en', 'name_before', 'alias', 'city', 'site', 'metro', 'phone', 'address', 'pathshort', 'yandex', 'map', 'foto', 'foto_street', 'foto_stage', 'foto_hall', 'foto_seats', 'shema', 'logo', 'type', 'menu', 'priority','link_vk','link_fb','link_insta', 'link_ok','link_yt', 'link_ok', 'link_gmap', 'link_vk_map', 'link_rider', 'link_photo','video','video_map','video_car'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Компания',
            'landing_id' => 'Лендинг',
            'favour' => 'Избранное',
            'name' => 'Название площадки',
            'name_before' => 'тип(перед назв.)',
            'alias' => 'Алиас',
            'city' => 'Город',
            'no_address' => 'Есть/нет адрес',
            'metro' => 'Метро',
            'phone' => 'Телефон',
            'status' => 'Статус',
            'closed' => 'Закрыто?',
            'address' => 'Адрес',
            'path' => 'Как пройти',
            'pathshort' => 'Как пройти коротко',
            'description' => 'Описание',
            'yandex' => 'Координаты яндекс карты',
            'map' => 'Схема проезда',
            'foto' => 'Фото на шапку',
            'shema' => 'Схема зала',
            'logo' => 'Логотип',
            'menu' => 'Пункт меню',
            'priority' => 'Приоритет в меню',
            'site' => 'Сайт без https',
            'info' => 'Информация',
            'serv_info' => 'Служебная нформация',
            'type' => 'Тип заведения(с маленькой буквы)',
            'link_vk' => 'link_vk: @igoevent без @',
            'link_fb' => 'link_fb: url полный',
            'link_insta' => 'insta: @igoevent без @',
            'link_ok' => 'ok: @igoevent без @',
            'scheme_width' => 'Ширина схемы в форме выбора мест'
        ];
    }

    public function getSeatings()
    {
        return $this->hasMany(Seatings::className(), ['place_id' => 'id']);
    }

    public function getBiblioevents()
    {
        return $this->hasMany(Biblioevents::className(), ['place_id' => 'id']);
    }

    public function getCities()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city']);
    }

    public function getLanding()
    {
        return $this->hasOne(Landing::className(), ['id' => 'landing_id'])->onCondition(['landing.status' => 1]);
    }

    public function PlaceOwner($alias, $city)
    {
        $ids = Companies::getIds();
        // $biblioevents = Biblioevents::find()->where(['company_id' => $ids])->all(); Виталик сохранил себе
        $ci = Cities::find()->where(['alias' => $city])->one();
        if(!is_numeric($alias)) {
            $pl = Places::find()->where(['alias' => $alias, 'city' => $ci->id])->one();
        } else {
          $pl = Places::find()->where(['id' => $alias])->one();  
        }
        if (!empty($pl)) {
            return $pl;
        }

    }

     public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['id' => 'post_id'])->viaTable('post_place', ['place_id' => 'id']);
    }
}
