<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ads".
 *
 * @property int $id
 * @property int $a_all В кабинете
 * @property int $city_id В каком городе
 * @property int $bib_id В каком событии 
 * @property int $biblioevent Какое событие показывать
 * @property int $sec_id В каком разделе показывать
 * @property int $user_id Кому показывать
 * @property int $click_u уникальные клики
 * @property int $view_u уникальные просмотры
 * @property int $click Сколько кликов
 * @property int $view Сколько просмотров
 * @property string $info
 * @property int $status 0 = выключена, 1 = активна, 2 = завершена, 3 = на проверке
 * @property int $del 1 = удалено
 * @property int $place_id В каком place показывать
 * @property int $favor Избранное
 * @property string $start С какой даты
 * @property string $end До какой даты
 * @property int $max_view Максимально кол-во показов
 * @property int $max_click Максимальное кол-во кликов
 */
class Ads extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['a_all', 'city_id', 'bib_id', 'biblioevent', 'sec_id', 'user_id', 'click_u', 'view_u', 'click', 'view', 'status', 'del', 'place_id', 'favor', 'max_view', 'max_click'], 'integer'],
            [['biblioevent'], 'required'],
            [['info'], 'string'],
            [['start', 'end'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'a_all' => 'Везде',
            'city_id' => 'City ID',
            'bib_id' => 'Bib ID',
            'biblioevent' => 'Biblioevent',
            'sec_id' => 'Sec ID',
            'user_id' => 'User ID',
            'click_u' => 'Click U',
            'view_u' => 'View U',
            'click' => 'Click',
            'view' => 'View',
            'info' => 'Info',
            'status' => 'Status',
            'del' => 'Del',
            'place_id' => 'Place ID',
            'favor' => 'Favor',
            'start' => 'Start',
            'end' => 'End',
            'max_view' => 'Max View',
            'max_click' => 'Max Click',
        ];
    }

    public static function ReAds($model)
    {
        // Найти всю рекламу этого события и выключить: status = 0
        $active_ads = Ads::find()->where(['biblioevent' => $model->id])->all();
        foreach ($active_ads as $active) {
            $active->status = 0;
            $active->save();
        }

        if ($model->a_all == 1) {
            
            $ads = Ads::find()->where(['a_all' => 1, 'biblioevent' => $model->id])->one();
            if (empty($ads)) {
                
                $ads = Ads::AddAds($model, null, null, null, null, $model->a_all);
            } else {
                $ads->status = 1;
                $ads->start = $model->a_start;
                $ads->end = $model->a_end;
                $ads->save();
            }
        }


        // ГОРОДА в которых показывать за исключением городов
        $yes_city = Ads::Explo($model->a_p_city);
        $no_city = Ads::Explo($model->a_m_city);
        $cities = array_diff($yes_city, $no_city);
        foreach ($cities as $city) {
            $ads = Ads::find()->where(['city_id' => $city, 'biblioevent' => $model->id])->one();
            if (empty($ads)) {
                $ads = Ads::AddAds($model, $city, null, null, null, null);
            } else {
                $ads->status = 1;
                $ads->start = $model->a_start;
                $ads->end = $model->a_end;
                $ads->save();
            }
        }

        // МЕСТА в которых показывать за исключением мест
        $yes_place = Ads::Explo($model->a_p_place);
        $no_place = Ads::Explo($model->a_m_place);
        $places = array_diff($yes_place, $no_place);
        foreach ($places as $place) {
            $ads = Ads::find()->where(['place_id' => $place, 'biblioevent' => $model->id])->one();
            if (empty($ads)) {
                $ads = Ads::AddAds($model, null, $place, null, null, null);
            } else {
                $ads->status = 1;
                $ads->start = $model->a_start;
                $ads->end = $model->a_end;
                $ads->save();
            }
        }

        // РАЗДЕЛЫ в которых показывать за исключением разделов
        $yes_sec = Ads::Explo($model->a_p_sec);
        $no_sec = Ads::Explo($model->a_m_sec);
        $secs = array_diff($yes_sec, $no_sec);
        foreach ($secs as $sec) {
            $ads = Ads::find()->where(['sec_id' => $sec, 'biblioevent' => $model->id])->one();
            if (empty($ads)) {
                $ads = Ads::AddAds($model, null, null, $sec, null, null);
            } else {
                $ads->status = 1;
                $ads->start = $model->a_start;
                $ads->end = $model->a_end;
                $ads->save();
            }
        }

        // СОБЫТИЯ в которых показывать за исключением событий
        $yes_bib = Ads::Explo($model->a_p_bib);
        $no_bib = Ads::Explo($model->a_m_bib);
        array_push($no_bib, $model->id);
        $bibs = array_diff($yes_bib, $no_bib);
        foreach ($bibs as $bib) {
            $ads = Ads::find()->where(['bib_id' => $bib, 'biblioevent' => $model->id])->one();
            if (empty($ads)) {
                $ads = Ads::AddAds($model, null, null, null, $bib, null);
            } else {
                $ads->status = 1;
                $ads->start = $model->a_start;
                $ads->end = $model->a_end;
                $ads->save();
            }
        }

        // КАБИНЕТЫ в которых показывать
        $yes_comp = Ads::Explo($model->a_company);
        foreach ($yes_comp as $comp) {
            $bibs = Biblioevents::find()->where(['company_id' => $comp])->all();
            foreach ($bibs as $bib) {
                $ads = Ads::find()->where(['bib_id' => $bib->id, 'biblioevent' => $model->id])->one();
                if (empty($ads)) {
                    $ads = Ads::AddAds($model, null, null, null, $bib->id, null);
                } else {
                    $ads->status = 1;
                    $ads->start = $model->a_start;
                    $ads->end = $model->a_end;
                    $ads->save();
                }
            }
        }
    }


    // Добавим новую запись в рекламу
    public static function AddAds($model, $city = false, $place = false, $sec = false, $bib = false, $a_all = false)
    {
        $ads = new Ads();
        $ads->biblioevent = $model->id;
        if (!empty($a_all)) { $ads->a_all = $a_all;}
        if (!empty($city)) { $ads->city_id = $city;}
        if (!empty($place)) { $ads->place_id = $place;}
        if (!empty($sec)) { $ads->sec_id = $sec;}
        if (!empty($bib)) { $ads->bib_id = $bib;}
        if ($model->a_start) { $ads->start = $model->a_start;}
        if ($model->a_end) { $ads->end = $model->a_end;}
        $ads->status = 1;
        if ($ads->save()) {
        } else {
            file_put_contents('test.txt', PHP_EOL.PHP_EOL.Date('d.m.Y H:i:s -адд ').json_encode($ads->getErrors(),JSON_UNESCAPED_UNICODE), FILE_APPEND);
        }
    }

    // ВЫВОД РЕКЛАМЫ

    // Реклама для города
    public static function City($city_id)
    {
        $bibs = Ads::find()->where(['city_id' => $city_id, 'status' => 1])->orWhere(['a_all' => 1])
        ->andWhere('DATE(start) <= DATE(NOW()) OR DATE(start) is NULL')->andWhere('DATE(end) >= DATE(NOW()) OR DATE(end) is NULL')->all();
        $b_ids = ArrayHelper::getColumn($bibs, 'biblioevent');
        $bibliovents = Biblioevents::find()->where(['biblioevents.id' => $b_ids])->joinWith('events')->andWhere('DATE(events.date) >= DATE(NOW())')->orderBy('a_priority desc')->all();
        return $bibliovents;
    }

    // Реклама для события
    public static function Event($bib_id)
    {
        $bibs = Ads::find()->where(['bib_id' => $bib_id, 'status' => 1])->orWhere(['a_all' => 1])
        ->andWhere('DATE(start) <= DATE(NOW()) OR DATE(start) is NULL')->andWhere('DATE(end) >= DATE(NOW()) OR DATE(end) is NULL')->all();
        $b_ids = ArrayHelper::getColumn($bibs, 'biblioevent');
        $bibliovents = Biblioevents::find()->where(['biblioevents.id' => $b_ids])->joinWith('img')->joinWith('events')->andWhere('DATE(events.date) >= DATE(NOW())')->andWhere('events.status > 0')->orderBy('a_priority desc')->all();
        return $bibliovents;
    }

    // Реклама для раздела
    public static function Section($section_id, $city_id)
    {
        $bibs = Ads::find()->where(['sec_id' => $section_id, 'status' => 1])->orWhere(['a_all' => 1])
        ->andWhere('DATE(start) <= DATE(NOW()) OR DATE(start) is NULL')->andWhere('DATE(end) >= DATE(NOW()) OR DATE(end) is NULL')->all();
        $b_ids = ArrayHelper::getColumn($bibs, 'biblioevent');
        $bibliovents = Biblioevents::find()->where(['biblioevents.id' => $b_ids, 'biblioevents.city' => $city_id])->joinWith('events')->andWhere('DATE(events.date) >= DATE(NOW())')->orderBy('a_priority desc')->all();
        return $bibliovents;
    }

    // Реклама для раздела
    public static function Place($place_id)
    {
        $bibs = Ads::find()->where(['place_id' => $place_id, 'status' => 1])->orWhere(['a_all' => 1])
        ->andWhere('DATE(start) <= DATE(NOW()) OR DATE(start) is NULL')->andWhere('DATE(end) >= DATE(NOW()) OR DATE(end) is NULL')->all();
        $b_ids = ArrayHelper::getColumn($bibs, 'biblioevent');
        $bibliovents = Biblioevents::find()->where(['biblioevents.id' => $b_ids])->joinWith('events')->andWhere('DATE(events.date) >= DATE(NOW())')->orderBy('a_priority desc')->all();
        return $bibliovents;
    }





    // запятые и дефисы превратим в массив номеров
    public static function Explo($field)
    {
        $exp_nums = explode(",", $field);
        $sq = null;
        foreach ($exp_nums as $n) {
            // проверим нет ли дефиса
            $defis = strpos($n,'-');
            if ($defis) {
                // если есть разобьем строку с дефисом
                $start_end = explode("-", $n);
                // восстановим все номера и первый тоже
                for ($i = $start_end[0]; $i <= $start_end[1]; $i++) { 
                    $sq[] = $i;
                }
            } else {
                $sq[] = $n;
            }
        }
        return $sq;
    }



}
