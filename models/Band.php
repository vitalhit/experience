<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "band".
 *
 * @property int $id
 * @property int $company_id
 * @property int $landing_id
 * @property string $date Дата образования
 * @property string $name
 * @property string $name_genitive
 * @property string $name_accusative
 * @property string $name_dative
 * @property string $name_instrumental
 * @property string $name_prepositional
 * @property string $eng_name
 * @property string $alias
 * @property string $city
 * @property string $image
 * @property string $imagept
 * @property int $category_id
 * @property string $url
 * @property string $phone
 * @property string $phone2
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_insta
 * @property string $link_yt
 * @property string $link_yandex
 * @property string $link_apple
 * @property string $link_spotify
 * @property string $link_photo
 * @property string $link_site
 * @property string $info
 * @property string $info_top
 * @property string $info_design
 * @property string $info_event
 * @property string $serv_info
 * @property string $anons
 * @property string $description
 * @property string $biography
 * @property string $rider
 * @property int $status 0 = неактивно, 1 = активно, 7 = по ссылке
 * @property string $create_at
 * @property string $link_vk_audio
 * @property string $video
 * @property string $video1
 * @property string $video2
 * @property string $video3
 * @property string $mark_future
 */
class Band extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'band';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'landing_id', 'category_id', 'status', 'mark_future', 'imagept'], 'integer'],
            [['date', 'create_at'], 'safe'],
            [['name'], 'required'],
            [['info','info_top','info_design','info_event','description','biography','serv_info', 'link_vk_audio', 'link_photo'], 'string'],
            [['rider'], 'string'],
            [['name', 'name_genitive', 'name_accusative', 'name_dative', 'name_instrumental', 'name_prepositional','eng_name', 'alias', 'image', 'url', 'phone', 'phone2', 'link_vk', 'link_fb', 'link_insta', 'link_yt', 'link_site', 'anons', 'link_yandex', 'link_apple', 'link_spotify', 'video', 'video1', 'video2', 'video3'], 'string', 'max' => 255],
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
            'name' => 'Название',
            'name_genitive' => 'Название(родительный)',
            'name_accusative' => 'Название(дательный)',
            'name_dative' => 'Название(винительный)',
            'name_instrumental' => 'Название(творительный)',
            'name_prepositional' => 'Название(предложный)',
            'anons' => 'Анонс',
            'eng_name' => 'Название на английском',
            'alias' => 'Алиас',
            'city' => 'Город',
            'image' => 'Картинка',
            'category_id' => 'ID Категории',
            'url' => 'url - не исполз',
            'phone' => 'Phone',
            'phone2' => 'Phone2',
            'link_vk' => 'Vk',
            'link_fb' => 'Fb',
            'link_insta' => 'Insta',
            'link_photo' => 'link photo',
            'link_yt' => 'YouTube',
            'info' => 'Информация(описание)',
            'serv_info' => 'Служебная информация',
            'biography' => 'Биография',
            'rider' => 'Райдер',
            'status' => 'Статус',
            'create_at' => 'Create At',
        ];
    }

    public function getArtists()
    {
        return $this->hasMany(Persons::className(), ['id' => 'person_id'])->viaTable('band_person', ['band_id' => 'id']);
    }

    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['id' => 'event_id'])->viaTable('band_event', ['band_id' => 'id']);
    }

    public function getCities()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city']);
    }

    public function getActiveevents()
    {
        return $this->hasMany(Events::className(), ['id' => 'event_id'])->viaTable('band_event', ['band_id' => 'id'])->andWhere('DATE(events.date) > (DATE(NOW()) - INTERVAL 1 DAY)')->andWhere(['events.status' => 1])->orderBy('events.date');
    }

    public function getBiblioevents()
    {
        return $this->hasMany(Biblioevents::className(), ['id' => 'biblioevent_id'])->viaTable('band_biblioevent', ['band_id' => 'id']);
    }

    public function getLanding()
    {
        return $this->hasOne(Landing::className(), ['id' => 'landing_id']);
    }

    public function BandOwner($alias)
    {
        $ids = Companies::getIds();
        
        if(!is_numeric($alias)) {
            $ba = Band::find()->where(['company_id' => $ids,'alias' => $alias])->one();
        } else {
          $ba = Band::find()->where(['company_id' => $ids,'id' => $alias])->one();
        }


        if (!empty($ba)) {
            return $ba;
        }

    }
    
}
