<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tour".
 *
 * @property int $id
 * @property int $company_id
 * @property int $landing_id
 * @property string $date 
 * @property string $date_start
 * @property string $date_end
 * @property string $date_title
 * @property string $name
 * @property string $second_title
 * @property string $name_accusative
 * @property string $name_dative
 * @property string $name_instrumental
 * @property string $name_prepositional
 * @property string $eng_name
 * @property string $alias
 * @property string $city
 * @property string $image
 * @property string $image1
 * @property string $image2
 * @property string $poster
 * @property int $category_id
 * @property string $url
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_yt
 * @property string $link_buy
 * @property string $info
 * @property string $anons
 * @property string $description
 * @property string $biography
 * @property string $rider
 * @property int $status 0 = неактивно, 1 = активно, 7 = по ссылке
 * @property string $create_at
 * @property string $link_site
 * @property string $link_vk_audio
 * @property string $video1
 * @property string $video2
 * @property string $video3
 */
class Tour extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tour';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id',  'status'], 'integer'],
            [['date', 'date_start','date_end','create_at'], 'safe'],
            [['name'], 'required'],
            [['info', 'link_vk_audio'], 'string'],
            [['rider'], 'string'],
            [['name', 'title_second', 'eng_name', 'date_title', 'alias', 'image', 'image1','image2','poster', 'url', 'link_vk', 'link_fb', 'link_insta', 'link_yt', 'link_buy', 'anons', 'video1', 'video2', 'video3'], 'string', 'max' => 255],
            [['city'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'landing_id' => 'Landing ID',
            'date' => 'Дата образования',
            'date_title' => 'Текст вместо даты',
            'name' => 'Название/name',
            'title_second' => '2ой заголовок/title_second',
            'anons' => 'Анонс',
            'eng_name' => 'Название на английском',
            'alias' => 'Алиас',
            'city' => 'Город',
            'category_id' => 'ID Категории',
            'url' => 'Сайт',
            'link_vk' => 'Vk',
            'link_fb' => 'Fb',
            'link_insta' => 'Insta',
            'link_yt' => 'YouTube',
            'info' => 'Информация(описание)',
            'rider' => 'Райдер',
            'status' => 'Статус',
            'create_at' => 'Create At',
        ];
    }

    public function getArtists()
    {
        return $this->hasMany(Persons::class, ['id' => 'person_id'])->viaTable('band_person', ['band_id' => 'id']);
    }

    public function getEvents()
    {
        return $this->hasMany(Events::class, ['id' => 'event_id'])->viaTable('tour_event', ['tour_id' => 'id']);
    }

    public function getCities()
    {
        return $this->hasOne(Cities::class, ['id' => 'city']);
    }

    public function getActiveevents()
    {
        return $this->hasMany(Events::class, ['id' => 'event_id'])->viaTable('band_event', ['band_id' => 'id'])->andWhere('DATE(events.date) > (DATE(NOW()) - INTERVAL 1 DAY)')->andWhere(['events.status' => 1])->orderBy('events.date');
    }

    public function getLanding()
    {
        return $this->hasOne(Landing::class, ['id' => 'landing_id']);
    }
    
}
