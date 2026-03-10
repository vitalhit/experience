<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contragent".
 *
 * @property int $id
 * @property int $company_id
 * @property string $brand
 * @property int $brand_id
 * @property string $name
 * @property string $name_long
 * @property string $company_type
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $jaddress
 * @property string $faddress
 * @property string $man
 * @property string $man_genitive
 * @property string $position
 * @property string $nds
 * @property string $bank
 * @property string $bik
 * @property string $korr
 * @property string $raschet
 * @property string $date
 * @property int $global
 * @property string $doc_basis
 * @property integer $doc_basis_use
 * @property string $doc_type
 * @property string $doc_number
 * @property string $doc_place
 * @property string $doc_date
 * @property string $doc_place_number
 * @property string $birth_date
 * @property string $birth_place
 * @property int $status
 * @property int $status_edm
 * @property int $status_fill
 * @property string $info
 * @property string $phone
 * @property string $mail
 */
class Contragent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contragent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'name'], 'required'],
            [['company_id', 'status', 'status_edm', 'status_fill', 'global', 'doc_basis_use','brand_id', 'phone'], 'integer'],
            [['date','birth_date','doc_date' ], 'safe'],
            [['info', 'name_long','company_type'], 'string'],
            [['name','brand', 'inn', 'kpp', 'ogrn', 'jaddress', 'faddress', 'man', 'man_genitive', 'position', 'nds', 'bank', 'bik', 'korr', 'raschet', 'name_long','company_type', 'doc_basis','birth_place', 'doc_place_number','doc_place','doc_number','doc_type','doc_basis', 'mail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'ID компании',
            'brand' => 'Название бренда',
            'name' => 'Название',
            'name_long' =>'Название юр. лица полностью',
            'company_type' => 'Тип юр. лица',
            'raschet' => 'Cчет',
            'bank' => 'Банк',
            'man' => 'ФИО ответственного за подпись договора',
            'man_genitive' => 'ФИО ответственного(родительный)/man_genitive',
            'date' => 'Дата',
            'status' => 'Статус: 0 - удален, 1 - акивен',
            'status_edm' => 'Электронный документооборот',
            'status_fill' => 'Статус заполнения',
            'info' => 'Дополнительная информация',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'ogrn' => 'ОГРН',
            'jaddress' => 'Юр. адрес',
            'faddress' => 'Почтовый адрес',
            'position' => 'Должность ответственного лица',
            'birth_place' => 'Место рождения',
            'nds' => 'НДС',
            'doc_number' =>'Серия и номер паспорта',
            'doc_basis' => 'Подпись на основании',
            
            'doc_type' => 'Тип документа',
            'doc_place' => 'Место выдачи',
            'doc_date' => 'Дата выдачи документа',
            'bik' => 'БИК',
            'korr' => 'Корр. счет',
            'doc_place_number' =>'код подразделения',
        ];
    }

    // Контрагенты в форме
    public static function Map()
    {
        $contragents = Contragent::find()->where(['company_id' => Companies::getCompanyId()])->orwhere(['global' => '1'])->all();
        foreach ($contragents as $contragent) {
            if ( $contragent->name != 'Rename') {
            $result[$contragent->id] = $contragent->name.' - '.$contragent->id.'('.$contragent->status_fill.'|'.$contragent->global.')';
            }
        }
        return $result;
    }

}
