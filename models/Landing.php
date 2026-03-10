<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "landing".
 *
 * @property int $id
 * @property string $image
 * @property int $imagept
 * @property string $title
 * @property string $anons
 * @property string $text
 * @property string $text2
 * @property string $video1
 * @property string $video2
 * @property string $video3
 * @property string $video1html
 * @property string $video2html
 * @property string $video3html
 * @property string $videotitle
 * @property string $imagetitle
 * @property string $image1
 * @property string $image1title
 * @property string $image1link
 * @property string $image1author
 * @property string $image2title
 * @property string $image2link
 * @property string $image2author
 * @property string $image2
 * @property string $image3title
 * @property string $image3link
 * @property string $image3author
 * @property string $image3
 * @property string $image4title
 * @property string $image4link
 * @property string $image4author
 * @property string $image4
 * @property string $image5title
 * @property string $image5link
 * @property string $image5author
 * @property string $image5
 * @property string $image6title
 * @property string $image6link
 * @property string $image6author
 * @property string $image6
 * @property string $image7title
 * @property string $image7link
 * @property string $image7author
 * @property string $image7
 * @property string $image8
 * @property string $image8title
 * @property string $image8link
 * @property string $image8author
 * @property string $image9
 * @property string $image9title
 * @property string $image9link
 * @property string $image9author
 * @property string $image10
 * @property string $image10title
 * @property string $image10link
 * @property string $image10author
 * @property string $link_kg
 * @property string $link_site
 * @property string $link_vk
 * @property string $link_fb
 * @property string $link_yt
 * @property string $link_insta
 * @property string $link_tg
 * @property string $phone
 * @property string $seotitle
 * @property string $seodesc
 * @property string $seokey
 * @property string $ogtitle
 * @property string $ogdescription
 * @property string $ogimage
 * @property int $status
 * @property string $info_top
 * @property string $info_client
 * @property string $info_program
 * @property string $info_band
 * @property string $info_tariff
 * @property string $info_org
 * @property int $info_client_show
 * @property int $info_program_show
 * @property int $info_band_show
 * @property int $info_tariff_show
 * @property int $info_org_show
 * @property string $bg_color
 */
class Landing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'landing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text','text2', 'info_client','info_top','info_program', 'info_band','info_tariff','info_org','video1html','video2html','video3html'], 'string'],
            [['image', 'title', 'anons', 'video1', 'video2', 'video3', 'videotitle', 'imagetitle', 'image1', 'image1title', 'image1link', 'image1author', 'image2title', 'image2link', 'image2author', 'image2', 'image3title', 'image3link', 'image3author', 'image3', 'image4title', 'image4link', 'image4author', 'image4', 'image5title', 'image5link', 'image5author', 'image5', 'image6title', 'image6link', 'image6author', 'image6', 'image7title', 'image7link', 'image7author', 'image7', 'image8', 'image8title', 'image8link', 'image8author', 'image9', 'image9title', 'image9link', 'image9author', 'image10', 'image10title', 'image10link', 'image10author', 'link_kg', 'link_site', 'link_vk', 'link_fb', 'link_yt', 'link_insta', 'link_tg', 'phone', 'seotitle', 'seodesc', 'seokey', 'ogtitle', 'ogdescription', 'ogimage'], 'string', 'max' => 255],
            [['bg_color'],  'string', 'max' => 6],
            [['status','imagept','info_client_show','info_program_show', 'info_band_show','info_tariff_show','info_org_show'], 'integer'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Топовая картинка',
            'title' => 'Заголовок',
            'anons' => 'Анонс под заголовком',
            'text' => 'Описание',
            'text2' => 'Второе описание',
            'video1' => 'id видео из youtube',
            'video2' => 'id видео из youtube',
            'video3' => 'id видео из youtube',
            'videotitle' => 'Заголовок видео блока',
            'imagetitle' => 'Заголовок фото блока',
            'image1' => 'Фото 1 ',
            'image1title' => 'Фото 1 заголовок',
            'image1link' => 'Фото 1 ссылка',
            'image1author' => 'Фото 1 ФИО автора',
            'image2title' => 'Фото 2 заголовок',
            'image2link' => 'Фото 2 ссылка',
            'image2author' => 'Фото 2 ФИО автора',
            'image2' => 'Фото 2 ',
            'image3title' => 'Фото 3 заголовок',
            'image3link' => 'Фото 3 ссылка',
            'image3author' => 'Фото 3 ФИО автора',
            'image3' => 'Фото 3 ',
            'image4title' => 'Фото 4 заголовок',
            'image4link' => 'Фото 4 ссылка',
            'image4author' => 'Фото 4 ФИО автора',
            'image4' => 'Фото 4 ',
            'image5title' => 'Фото 5 заголовок',
            'image5link' => 'Фото 5 ссылка',
            'image5author' => 'Фото 5 ФИО автора',
            'image5' => 'Фото 5 ',
            'image6title' => 'Фото 6 заголовок',
            'image6link' => 'Фото 6 ссылка',
            'image6author' => 'Фото 6 ФИО автора',
            'image6' => 'Фото 6 ',
            'image7title' => 'Фото 7 заголовок',
            'image7link' => 'Фото 7 ссылка',
            'image7author' => 'Фото 7 ФИО автора',
            'image7' => 'Фото 7 ',
            'image8' => 'Фото 8 ',
            'image8title' => 'Фото 8 заголовок',
            'image8link' => 'Фото 8 ссылка',
            'image8author' => 'Фото 8 ФИО автора',
            'image9' => 'Фото 9 ',
            'image9title' => 'Фото 9 заголовок',
            'image9link' => 'Фото 9 ссылка',
            'image9author' => 'Фото 9 ФИО автора',
            'image10' => 'Фото 1 0',
            'image10title' => 'Фото 10 заголовок',
            'image10link' => 'Фото 10 ссылка',
            'image10author' => 'Фото 10 ФИО автора',
            'phone' => 'Phone',
            'seotitle' => 'Title',
            'seodesc' => 'Description',
            'seokey' => 'KeyWords',
            'ogtitle' => 'og:title',
            'ogdescription' => 'og:description',
            'ogimage' => 'og:image(1074x480)',
            'status' => 'Статус',
            'imagept' => 'Отступ фоновой картинки сверху в %'
        ];
    }

    public function getImg()
    {
        return $this->hasOne(Img::className(), ['image' => 'image']);
    }
}
