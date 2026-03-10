<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "companies".
 *
 * @property int $id
 * @property string $name
 * @property string $brand
 * @property int $person_id
 * @property string $dogovor
 * @property int $dogovorstatus 0 не заключен, 1 подтвержден, 2 отклонен
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $jaddress
 * @property string $faddress
 * @property string $man
 * @property string $position
 * @property string $nds
 * @property string $bank
 * @property string $bik
 * @property string $korr
 * @property string $raschet
 * @property string $date
 * @property string $date_closed
 * @property int $status
 * @property int $type
 * @property string $info
 */
class Companies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date', 'date_closed'], 'safe'],
            [['person_id', 'status', 'dogovorstatus', 'type'], 'integer'],
            [['info'], 'string'],
            [['name', 'brand', 'dogovor', 'inn', 'kpp', 'ogrn', 'jaddress', 'faddress', 'man', 'position', 'nds', 'bank', 'bik', 'korr', 'raschet'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название ИП или ООО',
            'brand' => 'Бренд',
            'person_id' => 'ID профиля контактного лица',
            'dogovor' => 'Договор',
            'dogovorstatus' => 'Статус договора',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'ogrn' => 'ОГРН или ОГРНИП',
            'jaddress' => 'Юр. адрес',
            'faddress' => 'Почтовый адрес',
            'man' => 'ФИО подписывающего',
            'position' => 'Должность',
            'nds' => 'НДС',
            'bank' => 'БАНК',
            'bik' => 'БИК',
            'korr' => 'Корр. счет',
            'raschet' => 'Расчетный счет',
            'date' => 'Дата начала работы',
            'status' => 'Статус',
            'type' => 'Тип',
            'info' => 'Инфо',
        ];
    }


    public static function Front()
    {
        if (Yii::$app->user->id) {
            return Companies::find()->where(['id' => Companies::getIds()])->all();
        }
    }


    public static function Propis($sum)
    {
        return floor($sum) 
        . \Yii::t('app', ' ({n, spellout}) {n, plural, =0{рублей} one{рубль} few{рубля} many{рублей} other{рубля}} ',  ['n' => floor($sum)]) 
        . floor(($sum * 100) - (floor($sum) * 100)) 
        . \Yii::t('app', ' ({n, spellout}) {n, plural, =0{копеек} one{копейка} few{копейки} many{копеек} other{копеек}}',  ['n' => floor(($sum * 100) - (floor($sum) * 100)) ]);
    }


    public static function getAll()
    {
        $all_comps = Companies::find()->all();

        foreach ($all_comps as $comp) {
            if (!empty($comp->brand)) {
                $result[$comp->id] = $comp->brand;
            } elseif (!empty($comp->name)) {
                $result[$comp->id] = $comp->name;
            }
        }
        return $result;
    }

    public static function getIds()
    {
        $company_ids = CompanyUser::find()->where(['user_id' => Yii::$app->user->id])->asArray()->all();
        $ids = ArrayHelper::getColumn($company_ids, 'company_id');
        return $ids;
    }

    public static function setCompany($id)
    {
        $ids = self::getIds();
        if (in_array($id, $ids)) {
            $company = Companies::findOne($id);
            $user = Users::findOne(Yii::$app->user->id);
            $user->company_active = $company->id;
            $user->save();
            return $company;
        }
        return;
    }

    public static function getCompany()
    {
        $user = Users::findOne(Yii::$app->user->id);
        if (empty($user)) {
            return null;
        }
        $company = Companies::findOne($user->company_active);
        if (!empty($company)) {
            return $company;
        }
        return;
    }

    public static function getCompanyId()
    {
        $user = Users::findOne(Yii::$app->user->id);
        $company = Companies::findOne($user->company_active);
        if (!empty($company)) {
            return $company->id;
        }
        return;
    }

    // АКТ
    public static function CompAkt($id, $start, $end = false)
    {
        $company = Companies::findOne($id);
        // $companies = Companies::find()->where(['global_id' => $company->global_id])->orderBy('date')->all();
        // $c_ids = ArrayHelper::getColumn($companies, 'id');
        $c_ids = $company->id;
        $e_ids = Events::getIds($c_ids);
        $akt_end = date('Y-m-d H:i:s', strtotime("$start +1 month -1 day"));
        if (empty($end)) {
            $end = date('Y-m-d H:i:s', strtotime("$start +1 month"));
        }

        // Сумма билетов
        $t_sum = Tickets::Sum($e_ids, 5, $start, $end);
        $t_sum_p = Companies::Propis($t_sum);
        $t_sum_yr = 0; // юр.лица
        $t_sum_yr_p = Companies::Propis($t_sum_yr);

        // Остаток на начало
        $ost = Tickets::Sum($e_ids, 5, '1970-01-01', $start) 
        - CompanyPayments::Sum($c_ids, 3, '1970-01-01', $start)
        - round(Tickets::Sum($e_ids, 5, '1970-01-01', $start) * $company->percent/100) 
        - round(Tickets::Sum($e_ids, 7, '1970-01-01', $start) * 0.1);
        $ost_p = Companies::Propis($ost);

        // Cумма вознаграждения Агента 
        $vozn = $t_sum * $company->percent/100;
        $vozn_p = Companies::Propis($vozn);

        //  Cумма возвратов
        $t_back = Tickets::Sum($e_ids, 7, $start, $end);
        $t_back_p = Companies::Propis($t_back);

        // Вознаграждение за возвраты (% агенту)
        $vozn_back = round($t_back * 0.1);
        $vozn_back_p = Companies::Propis($vozn_back);

        $vozn_all = $vozn + $vozn_back;
        $vozn_all_p = Companies::Propis($vozn_all);

        // Денежные средства, удержанные Агентом для взаимозачета
        $block = 0;
        $block_p = Companies::Propis($block);

        // Сумма перечисления 
        $pere = CompanyPayments::Sum($c_ids, 3, $start, $end);
        $pere_p = Companies::Propis($pere);

        // Остаток у Агента
        $ost_end = $ost + $t_sum - $vozn - $vozn_back - $block - $pere;
        $ost_end_p = Companies::Propis($ost_end);
        
        $money = array(
         'ost' => $ost,
         'ost_p' => $ost_p,
         't_sum' => $t_sum,
         't_sum_p' => $t_sum_p,
         't_sum_yr_p' => $t_sum_yr_p,
         'vozn' => $vozn,
         'vozn_p' => $vozn_p,
         't_back' => $t_back,
         't_back_p' => $t_back_p,
         'vozn_back' => $vozn_back,
         'vozn_back_p' => $vozn_back_p,
         'vozn_all' => $vozn_all,
         'vozn_all_p' => $vozn_all_p,
         'block' => $block,
         'block_p' => $block_p,
         'pere' => $pere,
         'pere_p' => $pere_p,
         'ost_end' => $ost_end,
         'ost_end_p' => $ost_end_p
     );

        return ['company' => $company, 'start' => $start, 'akt_end' => $akt_end, 'money' => $money];
    }


    public function getcompanyUser()
    {
        return $this->hasMany(CompanyUser::className(), ['user_id' => 'id']);
    }

    public function getcompanyPayments()
    {
        return $this->hasMany(CompanyPayments::className(), ['company_id' => 'id']);
    }

    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id']);
    }

}
