<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "guide".
 *
 * @property int $id
 * @property int $person_id
 * @property string|null $description
 * @property string|null $photo
 * @property float $rating
 * @property int|null $experience_start_year
 * @property int $tours_count
 * @property int $guides_count
 * @property int $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Persons $person
 * @property Biblioevents[] $biblioevents
 */
class Guide extends ActiveRecord
{
    public static function tableName()
    {
        return 'guide';
    }

    public function rules()
    {
        return [
            [['person_id'], 'required'],
            [['person_id', 'experience_start_year', 'tours_count', 'guides_count', 'is_active'], 'integer'],
            [['rating'], 'number', 'min' => 0, 'max' => 5],
            [['description'], 'string'],
            [['photo'], 'string', 'max' => 255],
            [['person_id'], 'unique'],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persons::className(), 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['id' => 'person_id']);
    }

    public function getBiblioevents()
    {
        return $this->hasMany(Biblioevents::className(), ['id' => 'biblioevent_id'])
            ->viaTable('biblioevent_guide', ['guide_id' => 'id']);
    }

    /**
     * Получить опыт в годах
     */
    public function getExperienceYears()
    {
        if (!$this->experience_start_year) {
            return null;
        }
        return date('Y') - $this->experience_start_year;
    }
}
