<?php

namespace app\models;

use Yii;
use \VK\Client\VKApiClient;

/**
 * This is the model class for table "vk".
 *
 * @property int $id
 * @property int $company_id id рекламы
 * @property int $event_id
 * @property int $vk_id id юзера vk
 * @property int $group_id id группы vk
 * @property int $post_id id поста vk
 * @property int $user_id
 * @property string $name Название действия
 * @property int $type Тип действия - пока нету
 * @property string $url Ссылка на действие
 * @property int $raiting Юзер оценил 1-10
 * @property string $message Юзер написал
 * @property string $info Коммент куратора
 * @property string $create_at
 * @property int $status 1 новый, 2 готов, 3 на проверке, 4 в работе
 */
class Vk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'event_id', 'vk_id', 'group_id', 'post_id', 'user_id', 'type', 'raiting', 'status'], 'integer'],
            [['user_id', 'name'], 'required'],
            [['message', 'info'], 'string'],
            [['create_at'], 'safe'],
            [['name', 'url'], 'string', 'max' => 255],
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
            'event_id' => 'Event ID',
            'vk_id' => 'Vk ID',
            'group_id' => 'Group ID',
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'type' => 'Type',
            'url' => 'Url',
            'raiting' => 'Raiting',
            'message' => 'Message',
            'info' => 'Info',
            'create_at' => 'Create At',
            'status' => 'Status',
        ];
    }

    // Уведомления разрешены? и не отменены?
    public static function Noti($uid)
    {
        $noti = Vk::find()->where(['user_id' => $uid, 'type' => 1])->orderBy('id desc')->one();
        if (!empty($noti)) {
            $unnoti = Vk::find()->where(['user_id' => $uid, 'type' => 2])->andWhere(['>', 'id', $noti->id])->one();
            if (empty($unnoti)) {
                return $noti;
            }
        }
    }

    

    // Отправить уведомление
    public static function Send($m, $uid)
    {
        $access_token = 'eb8c5d15a6bee33bcec7e9bb9da2a80afa6a367df035239f3b6c0ef23ce6c3eab895cd70c62c9535d00a3';
        $vk = new VKApiClient();

        // $keyboard =  '{ 
        //  "one_time": false, 
        //  "buttons": [ 
        //      [{ 
        //          "action": { 
        //              "type": "text", 
        //              "payload": "{\"button\": \"1\"}", 
        //              "label": "Принять" 
        //          }, 
        //          "color": "positive" 
        //      }, 
        //      { 
        //          "action": { 
        //              "type": "text", 
        //              "payload": "{\"button\": \"2\"}", 
        //              "label": "Отклонить" 
        //          }, 
        //          "color": "negative" 
        //      }]
        //  ] 
        // }';

        $response = $vk->messages()->send($access_token, array( 
            'user_ids' => $uid,
            'random_id' => random_int(-2147483648, 2147483647),
            'message' => $m,
            // 'keyboard' => $keyboard
        ));
    }





    // Получить данные о пользователе по короткому имени
    public function UserGet($url)
    {
        // Если есть ID
        $pos = strpos($url, 'vk.com/id');
        if (!empty($pos)) { 
            // file_put_contents('test.txt', PHP_EOL.'ВК ПОС: '.json_encode($pos), FILE_APPEND);

            $vkid = str_replace("https://vk.com/id", "", $url);
            // file_put_contents('test.txt', PHP_EOL.'ВК ЕСТЬ: '.json_encode($vkid), FILE_APPEND);
            return $vkid;
        }

        // Если нет ID а есть короткое имя
        $pos = strpos($url, 'vk.com');
        if (!empty($pos)) { 
            // file_put_contents('test.txt', PHP_EOL.'ВК ИЩЕМ', FILE_APPEND);


            $vkid = str_replace("https://vk.com/", "", $url); 
            // file_put_contents('test.txt', PHP_EOL.'ВК ВКАЙДИ: '.json_encode($vkid), FILE_APPEND);

            $vk = new VKApiClient();
            $response = $vk->users()->get(Yii::$app->params['user_key'], array( 'user_ids' => $vkid ));
            if (!empty($response)) {
                // file_put_contents('test.txt', PHP_EOL.'ВК ОТВЕТ: '.json_encode($response), FILE_APPEND);
                // $model = null;
                // $model['user_id'] = $response[0]['id'];
                // if (!empty($response[0]['first_name'])) { $model['first_name'] = $response[0]['first_name']; }
                // if (!empty($response[0]['last_name'])) { $model['last_name'] = $response[0]['last_name']; }
                // if (!empty($response[0]['screen_name'])) { $model['screen_name'] = $response[0]['screen_name']; }
                // if (!empty($response[0]['sex'])) { $model['sex'] = $response[0]['sex']; }
                // if (!empty($response[0]['photo_max_orig'])) { $model['picture'] = $response[0]['photo_max_orig']; }
                // if (!empty($response[0]['timezone'])) { $model['timezone'] = $response[0]['timezone']; }
                return $response[0]['id'];
            }
        }
    }







}
