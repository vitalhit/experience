<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "projects".
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property int $place_id
 * @property int $owner_id
 * @property int $client_id
 * @property int $type
 * @property string $create_at
 * @property string $deadline
 * @property string $link_docs
 * @property int $status_id
 * @property string $result
 * @property string $info
 */
class Projects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'company_id', 'place_id', 'status_id'], 'required'],
            [['company_id', 'place_id', 'owner_id', 'client_id', 'type', 'status_id'], 'integer'],
            [['create_at', 'deadline'], 'safe'],
            [['result', 'info','link_docs'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'company_id' => 'Компания',
            'place_id' => 'Место',
            'owner_id' => 'Ответственный',
            'client_id' => 'Клиент',
            'type' => 'Тип',
            'create_at' => 'Создание',
            'deadline' => 'Окончание',
            'status_id' => 'Статус',
            'result' => 'Результат',
            'info' => 'Служебная информация',
        ];
    }
    
    public static function ProjectIds($company = false)
    {
        if (!empty($company)) {
            $projects = Projects::find()->where(['company_id' => $company->id])->all();
        } else {
            $projects = Projects::find()->where(['company_id' => Companies::getCompanyId()])->all();
        }
        return ArrayHelper::getColumn($projects, 'id');
    }

    public function ProjectIdsAll()
    {
        $projects = Projects::find()->where(['company_id' => Companies::getIds()])->all();
        return ArrayHelper::getColumn($projects, 'id');
    }




    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }
    
    public function getPlace()
    {
        return $this->hasOne(Places::className(), ['id' => 'place_id']);
    }
    
    public function getOwners()
    {
        return $this->hasOne(Users::className(), ['id' => 'owner_id']);
    }
    
    public function getStatus()
    {
        return $this->hasOne(ProjectsStatus::className(), ['id' => 'status_id']);
    }

    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['project_id' => 'id']);
    }
}
