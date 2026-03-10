<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * This is the model class for table "img".
 *
 * @property int $id
 * @property string $imgname
 * @property int $user_id
 * @property string $create_at
 * @property string $image
 * @property int $imagept
 * @property string $imgmid
 * @property string $imgsmall
 * @property string $imgrazdel
 * @property string $imgland
 * @property string $imgthumb
 * @property string $imgmeta
 * @property string $imgvk
 * @property string $imgfb
 * @property string $imgin
 * @property string $img474x350
 * @property string $img1350x720
 * @property string $img216x314
 * @property string $img1000x1000
 * @property string $img526x804
 * @property string $vk
 * @property string $fb
 * @property string $insta
 * @property string $title
 * @property string $alt
 * @property string $text
 * @property string $name
 * @property string $second_name
 * @property string $middle_name
 * @property string $user_url
 * @property string $original
 * @property string $url
 * @property int $status 1 = активно, 0 = удалено
 */
class Img extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'imagept'], 'integer'],
            [['create_at'], 'safe'],
            [['imgname', 'image', 'imgmid', 'imgsmall', 'imgrazdel', 'imgland', 'imgthumb', 'imgmeta', 'imgvk', 'imgfb', 'imgin', 'img1350x720', 'img474x350', 'img216x314', 'img526x804', 'img1000x1000', 'vk', 'fb', 'insta', 'title', 'alt', 'text', 'name', 'second_name', 'middle_name', 'user_url', 'original', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Картинка',
            'imgname' => 'Название картинки',
            'user_id' => 'User ID',
            'create_at' => 'Создано',
            'imgmid' => 'imgmid: Средняя 1000px',
            'imgsmall' => 'imgsmall: Маленькая 300px',
            'imgrazdel' => 'imgrazdel: Раздел',
            'imgthumb' => 'imgthumb: 100px',
            'imgland' => 'imgland: Лендинг',
            'imgmeta' => 'imgmeta: Мета',
            'imgvk' => 'Фото для встречи вк',
            'imgfb' => 'Фото для встречи FB',
            'imgin' => 'Фото для инстаграмма',
            'vk' => 'Ссылка на вк',
            'fb' => 'Ссылка на фб',
            'insta' => 'Ссылка на instagram',
            'title' => 'Title',
            'alt' => 'Alt',
            'text' => 'Подпись под фото на HTML странице',
            'name' => 'Имя фотографа',
            'second_name' => 'Фамилия фотографа',
            'middle_name' => 'Отчество фотографа',
            'user_url' => 'Ссылка на сайт фотографа',
            'original' => 'Ссылка на оригинал',
            'url' => 'url для фотографии',
            'status' => 'Status',
            'imagept' => 'Отступ фоновой картинки сверху в %'
        ];
    }


    // Сжимаем фото
    public static function crop1200($dir, $img)
    {
        $image = $dir . '/' . $img;

        $size = getimagesize($image); // Определяем размер картинки
        if (empty($size)) {
            return;
        }
        $width = $size[0]; // Ширина картинки
        $height = $size[1]; // Высота картинки

        $new_width = 1200;
        $scale = $width / $new_width;
        $new_height = $height * $scale;

        // сжать оригинал до $new_width и на 70%
        Image::resize($image, $new_width, $new_height, true)->save(Yii::getAlias($image), ['quality' => 70]);
    }



    public static function New($new_image)
    {
        // echo "<pre>";
        // print_r($new_image);
        // echo "</pre>";
        $img = UploadedFile::getInstance($new_image, 'image');
        if (!empty($img)) {
            //$new_image->image = Yii::$app->storage->saveImgFile($img, 0);
            $new_image->image = Yii::$app->storage->saveImgFile($img);
            // echo "<pre>";
            // print_r($new_image);
            // echo "</pre>";

            $ImgClass = Img::find()->where(['image' => $new_image->image])->one();
            if (empty($ImgClass)) {
                $ImgClass = new Img();
                $ImgClass->image = $new_image->image;
                $ImgClass->user_id = Yii::$app->user->id;
                $ImgClass->save();
                Img::ImgThumb($new_image->image);
            }
            return $ImgClass->image;
        }
    }


    public static function ImgThumb($img)
    {
        $image = Img::find()->where(['image' => $img])->one();

        if (!empty($image)) {
            $dot = strrpos($image->image, '.');
            $name = substr($image->image, 0, $dot);
            $ext = substr($image->image, -(strlen($image->image) - $dot));


            if (empty($image->imgmid)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 1000, null)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgmid' . $ext), ['quality' => 65]);
                $image->imgmid = $name . '_imgmid' . $ext;
            }

            if (empty($image->imgsmall)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 600, null)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgsmall' . $ext), ['quality' => 65]);
                $image->imgsmall = $name . '_imgsmall' . $ext;
            }

            if (empty($image->imgrazdel)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 384, 216)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgrazdel' . $ext), ['quality' => 65]);
                $image->imgrazdel = $name . '_imgrazdel' . $ext;
            }


            if (empty($image->imgthumb)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 100, 100)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgthumb' . $ext), ['quality' => 50]);
                $image->imgthumb = $name . '_imgthumb' . $ext;
            }

            if (empty($image->imgland)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 1300, null)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgland' . $ext), ['quality' => 65]);
                $image->imgland = $name . '_imgland' . $ext;
            }


            if (empty($image->img474x350)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 474, 350)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img474x350' . $ext), ['quality' => 65]);
                $image->img474x350 = $name . '_img474x350' . $ext;
            }
            if (empty($image->img1350x720)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 1350, 720)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img1350x720' . $ext), ['quality' => 65]);
                $image->img1350x720 = $name . '_img1350x720' . $ext;
            }
            if (empty($image->img216x314)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 216, 314)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img216x314' . $ext), ['quality' => 65]);
                $image->img216x314 = $name . '_img216x314' . $ext;
            }
            if (empty($image->img526x804)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 526, 804)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img526x804' . $ext), ['quality' => 65]);
                $image->img526x804 = $name . '_img526x804' . $ext;
            }
            if (empty($image->img1000x1000)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $image->image, 1000, 1000)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img1000x1000' . $ext), ['quality' => 65]);
                $image->img1000x1000 = $name . '_img1000x1000' . $ext;
            }
            $image->save();
        }
    }


    public function ReThumb()
    {
        $bibs = Biblioevents::find()->where('image is not null')->andWhere('id > 10')->andWhere('status >= 0')->all();
        $i = 0;
        foreach ($bibs as $bib) {
            if (strlen($bib->image) > 6) {
                $dot = strrpos($bib->image, '.');
                $name = substr($bib->image, 0, $dot);
                $ext = substr($bib->image, -(strlen($bib->image) - $dot));
                $img = Img::find()->where(['image' => $bib->image])->one();
                if (empty($img)) {
                    $img = new Img();
                    $img->image = $bib->image;
                    $img->save();
                }

                if (empty($img->imgmid)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $bib->image, 1000, null)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgmid' . $ext), ['quality' => 65]);
                    $img->imgmid = $name . '_imgmid' . $ext;
                    $i++;
                }

                if (empty($img->imgsmall)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $bib->image, 600, null)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgsmall' . $ext), ['quality' => 65]);
                    $img->imgsmall = $name . '_imgsmall' . $ext;
                    $i++;
                }

                if (empty($img->imgrazdel)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $bib->image, 384, 216)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgrazdel' . $ext), ['quality' => 65]);
                    $img->imgrazdel = $name . '_imgrazdel' . $ext;
                    $i++;
                }

                if (empty($img->imgthumb)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $bib->image, 100, 100)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgthumb' . $ext), ['quality' => 65]);
                    $img->imgthumb = $name . '_imgthumb' . $ext;
                    $i++;
                }

                if (empty($img->imgland)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $bib->image, 1300, null)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgland' . $ext), ['quality' => 65]);
                    $img->imgland = $name . '_imgland' . $ext;
                    $i++;
                }
                if (empty($img->img474x350)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 474, 350)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img474x350' . $ext), ['quality' => 65]);
                    $img->img474x350 = $name . '_img474x350' . $ext;
                }
                if (empty($img->img1350x720)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 1350, 720)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img1350x720' . $ext), ['quality' => 65]);
                    $img->img1350x720 = $name . '_img1350x720' . $ext;
                }
                 if (empty($img->img526x804)) {
                Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 526, 804)
                ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img526x804' . $ext), ['quality' => 65]);
                $img->img526x804 = $name . '_img526x804' . $ext;
                }
               
                if (empty($img->img1000x1000)) {
                    Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 1000, 1000)
                    ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img1000x1000' . $ext), ['quality' => 65]);
                    $image->img1000x1000 = $name . '_img1000x1000' . $ext;
                }

                $img->save();
            }
        }
        return $i;
    }

    public static function ReThumbOne($id)
    {
        $img = Img::find()->where(['id' => $id])->one();
        $dot = strrpos($img->image, '.');
        $name = substr($img->image, 0, $dot);
        $ext = substr($img->image, -(strlen($img->image) - $dot));

        $i = 0;
        if (empty($img->imgmid)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 1000, null)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgmid' . $ext), ['quality' => 65]);
            $img->imgmid = $name . '_imgmid' . $ext;
            $i++;
        }

        if (empty($img->imgsmall)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 600, null)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgsmall' . $ext), ['quality' => 65]);
            $img->imgsmall = $name . '_imgsmall' . $ext;
            $i++;
        }

        if (empty($img->imgrazdel)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 384, 216)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgrazdel' . $ext), ['quality' => 65]);
            $img->imgrazdel = $name . '_imgrazdel' . $ext;
            $i++;
        }

        if (empty($img->imgthumb)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 100, 100)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgthumb' . $ext), ['quality' => 65]);
            $img->imgthumb = $name . '_imgthumb' . $ext;
            $i++;
        }

        if (empty($img->imgland)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 1300, null)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_imgland' . $ext), ['quality' => 65]);
            $img->imgland = $name . '_imgland' . $ext;
            $i++;
        }
        if (empty($img->img474x350)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 474, 350)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img474x350' . $ext), ['quality' => 65]);
            $img->img474x350 = $name . '_img474x350' . $ext;
        }
        if (empty($img->img1350x720)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 1350, 720)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img1350x720' . $ext), ['quality' => 65]);
            $img->img1350x720 = $name . '_img1350x720' . $ext;
        }
        if (empty($img->img216x314)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 216, 314)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img216x314' . $ext), ['quality' => 65]);
            $img->img216x314 = $name . '_img216x314' . $ext;
        }
        if (empty($img->img526x804)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 526, 804)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img526x804' . $ext), ['quality' => 65]);
            $img->img526x804 = $name . '_img526x804' . $ext;
        }
        if (empty($img->img1000x1000)) {
            Image::thumbnail(Yii::$app->params['storagePath'] . $img->image, 1000, 1000)
            ->save(Yii::getAlias(Yii::$app->params['storagePath'] . $name . '_img1000x1000' . $ext), ['quality' => 65]);
            $img->img1000x1000 = $name . '_img1000x1000' . $ext;
        }
        $img->save();
        return $i;
    }

}
