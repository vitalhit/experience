<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $project_id
 * @property int $parent_id
 * @property int $creator_id
 * @property int $owner_id
 * @property int $person_id
 * @property int $client_id
 * @property int $next
 * @property int $prev
 * @property string $start
 * @property string $end
 * @property string $time
 * @property int $smena_id
 * @property int $dohod
 * @property int $rashod
 * @property int $priority
 * @property string $name
 * @property int $quality
 * @property int $status_id
 * @property int $todo_id
 * @property int $type_id
 * @property int $delay
 * @property string $result
 * @property string $url
 * @property string $info
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasks';
    }




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'project_id'], 'required'],
            [['project_id', 'parent_id', 'creator_id', 'owner_id', 'person_id', 'client_id', 'next', 'prev', 'smena_id', 'dohod', 'rashod', 'priority', 'quality', 'status_id', 'todo_id', 'type_id', 'delay'], 'integer'],
            [['start', 'end', 'time'], 'safe'],
            [['name', 'result', 'url', 'info'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Проект',
            'parent_id' => 'Родитель',
            'creator_id' => 'Создатель',
            'owner_id' => 'Ответственный',
            'person_id' => 'ID Гостя',
            'client_id' => 'ID Клиента',
            'next' => 'Следующая',
            'prev' => 'Предыдущая',
            'start' => 'Начало',
            'end' => 'Окончание',
            'time' => 'Заняло часов',
            'smena_id' => 'Смена',
            'dohod' => 'Доход',
            'rashod' => 'Расход',
            'priority' => 'Приоритет',
            'name' => 'Название',
            'quality' => 'Качество',
            'status_id' => 'Статус',
            'type_id' => 'Тип',
            'delay' => 'Задержка',
            'result' => 'Результат',
            'url' => 'Url',
            'info' => 'Служебная информация',
        ];
    }
    
    public function getOwners()
    {
        return $this->hasOne(Users::className(), ['id' => 'owner_id']);
    }
    
    public function getCreator()
    {
        return $this->hasOne(Users::className(), ['id' => 'creator_id']);
    }
    
    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['user_id' => 'id']);
    }
    
    public function getClient()
    {
        return $this->hasOne(Clients::className(), ['id' => 'user_id']);
    }
    
    public function getStatus()
    {
        return $this->hasOne(TasksStatus::className(), ['id' => 'status_id']);
    }
    
    public function getType()
    {
        return $this->hasOne(TasksType::className(), ['id' => 'type_id']);
    }

    public function getProjects()
    {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }

    public function getSteps()
    {
        return $this->hasMany(Tasks::className(), ['parent_id' => 'id']); // Получить все шаги (подтаски) этого таска
    }

    public function getDocs()
    {
        return $this->hasMany(Docs::className(), ['task_id' => 'id'])->onCondition('docs.status > 0');
    }

    public function getClients()
    {
        return $this->hasMany(Clients::className(), ['id' => 'client_id'])->viaTable('task_client', ['task_id' => 'id']);
    }

    public function getTaskClient()
    {
        return $this->hasMany(TaskClient::className(), ['task_id' => 'id']);
    }

    public function getTaskBiblioevent()
    {
        return $this->hasMany(TaskBiblioevent::className(), ['task_id' => 'id']);
    }

    public function getTaskLink()
    {
        return $this->hasMany(TaskLink::className(), ['task_id' => 'id']);
    }
}
