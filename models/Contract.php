<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contract".
 *
 * @property int $id
 * @property string $indx
 * @property int $doc_template
 * @property string $name_short
 * @property double $percent
 * @property double $tax
 * @property string $signer
 * @property string $basis
 * @property string $name
 * @property int $person_id
 * @property string $global_id 
 * @property string $dogovor
 * @property int $dogovorstatus 
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
 * @property int $status
 * @property int $type
 * @property string $info
 * @property string $buh_name
 * @property string $buh_info
 * @property string $buh_mail
 * @property string $doc_type
 * @property string $doc_mumber
 * @property string $doc_birhday
 * @property string $doc_place
 * @property int $doc_date
 * @property int $doc_place_number
 
 */
class Contract extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'global_id'], 'required'],
            [['person_id', 'dogovorstatus', 'status', 'type', 'doc_place_number','doc_template', 'percent', 'tax'], 'integer'],
            [['date'], 'safe'],
            [['info','buh_info'], 'string'],
            [['name', 'global_id', 'dogovor', 'inn', 'kpp', 'ogrn', 'jaddress', 'faddress', 'man', 'position', 'nds', 'bank', 'bik', 'korr', 'raschet', 'buh_name', 'doc_date',  'name_short', 'signer', 'basis', 'doc_place', 'doc_birhday', 'doc_mumber', 'doc_type', 'buh_mail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'person_id' => 'Person ID',
            'global_id' => 'Global ID',
            'dogovor' => 'Dogovor',
            'dogovorstatus' => 'Dogovorstatus',
            'inn' => 'Inn',
            'kpp' => 'Kpp',
            'ogrn' => 'Ogrn',
            'jaddress' => 'Jaddress',
            'faddress' => 'Faddress',
            'man' => 'Man',
            'position' => 'Position',
            'nds' => 'Nds',
            'bank' => 'Bank',
            'bik' => 'Bik',
            'korr' => 'Korr',
            'raschet' => 'Raschet',
            'date' => 'Date',
            'status' => 'Status',
            'type' => 'Type',
            'info' => 'Info',
        ];
    }
}
