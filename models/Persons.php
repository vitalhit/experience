<?php

namespace app\models;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "persons".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $company_id
 * @property string $name
 * @property string $city
 * @property string $second_name
 * @property string $middle_name
 * @property string $mail
 * @property string $phone
 * @property integer $discount
 * @property string $status
 * @property string $groups
 * @property integer $sex
 * @property string $birthday
 * @property string $vishes
 * @property integer $froms_id
 * @property integer $sendmail
 * @property string $info
 * @property string $serv_info
 * @property integer $inside
 * @property string $lastvisit
 * @property integer $sum_visits
 * @property integer $sum_tickets
 * @property integer $sum_sells
 * @property integer $sum_abonements
 * @property integer $sum_rents
 * @property string $image
 */
class Persons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'persons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'company_id', 'second_name'], 'required'],
            [['user_id', 'company_id', 'discount', 'sex', 'froms_id', 'sendmail', 'inside', 'sum_visits', 'sum_tickets', 'sum_sells', 'sum_abonements', 'sum_rents', 'vk_id'], 'integer'],
            [['birthday', 'lastvisit'], 'safe'],
            [['vishes', 'info', 'serv_info'], 'string'],
            [['name', 'city', 'second_name', 'middle_name', 'mail', 'phone', 'status', 'groups', 'image', 'link_vk', 'link_fb', 'link_insta'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID личного кабинета',
            'company_id' => 'ID компании',
            'city' => 'Город',
            'second_name' => 'Фамилия',
            'name' => 'Имя *',
            'middle_name' => 'Отчество',
            'mail' => 'E-mail',
            'phone' => 'Телефон',
            'birthday' => 'День рождения',
            'groups' => 'Группа (кино, джаз и пр.)',
            'sex' => 'Пол',
            'discount' => 'Скидка (число)',
            'status' => 'Статус',
            'vishes' => 'Ваши пожелания',
            'froms_id' => 'Откуда узнали',
            'sendmail' => 'Получать рассылку',
            'info' => 'Служебная информация',
            'inside' => 'Inside',
            'lastvisit' => 'Последний визит',
            'sum_visits' => 'Сумма визитов',
            'sum_tickets' => 'Сумма билетов',
            'sum_sells' => 'Сумма покупок',
            'sum_abonements' => 'Сумма абонементов',
            'sum_rents' => 'Сумма аренд',
            'image' => 'Фотография',
            'link_vk' => '@link_vk',
            'link_fb' => 'link_fb',
            'link_insta' => 'link_insta',
            'vk_id' => 'vk_id'
        ];
    }


    // Отправляем письмо
    public function sendMail($view, $subject, $params = [])
    {

        $result = \Yii::$app->mailer->compose([
            'html' => 'views/' . $view . '-html',
            'text' => 'views/' . $view . '-text',
        ], $params)->setTo([$this->mail => $this->mail])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Igoevent.com'])
            ->setSubject($subject)
            ->send();
        return $result;
    }



    // Отправляем письмо со всеми билетами с одинаковым order_id!
    public static function ticketMail($tickets)
    {
        
        $template_id = Null;
        foreach($tickets as $t) {
        	
            $template_id = $t->template_id;
        } 

        $person = Persons::findOne($tickets[0]->user_id);
        
        $event = Events::find()->where(['events.id' => $tickets[0]->event_id])->joinWith('biblioevents')->joinWith('biblioevents.places')->joinWith('biblioevents.letterBuy')->one();


        if (!empty($event->place_id)) {
            $place = Places::find()->where('id = ' . $event->place_id)->one();
        } else {
            $place = Places::find()->where('id = ' . $event->biblioevents->place_id)->one();
        }

        $params = array('tickets' => $tickets, 'person' => $person, 'event' => $event, 'place' => $place);
        $path_to_email_template = '@app/mail/views/ticketsmail-html';
        
        if ($template_id == 3 ){
            $path_to_email_template = '@app/mail/views/ticketprereg-html';
        }
         if ($template_id == 7 ){
            $path_to_email_template = '@app/mail/views/ticketreg-html';
        }

        $text = \Yii::$app->view->render($path_to_email_template, $params);

        $mail = \Yii::$app->mailer->compose()
            ->setTo([$person->mail => $person->mail])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Сервис igoevent.com'])
            ->setSubject('Билеты/регистрация! ' . $event->biblioevents->name . '(' . $event->id . ')')
            ->setHtmlBody($text)
            ->send();

        Messages::saveMail($person->id, $tickets[0]->order_id, 'Спасибо за покупку! ' . $event->biblioevents->name . '(' . $event->id . ')', $text, 2);

        foreach ($tickets as $t) {
            $t->send = 1;
            $t->save();
        }
        return $mail;
    }

    // Создаем нового гостя (профиль)
    public static function createPerson($post)
    {
        //file_put_contents('test.txt', PHP_EOL . 'Тикет Сит ' . PHP_EOL . PHP_EOL . json_encode($post) . PHP_EOL, FILE_APPEND);

        $person = new Persons();

        $person->name = $post['name'];
        if ($post['second_name']) {
            $person->second_name = $post['second_name'];
        } else {
            $person->second_name = '-';
        };
        $person->company_id = $post['company_id'];
        $newmail = str_replace(" ", "", $post['mail']);
        $person->mail = trim($newmail);

        $person->phone = $post['phone'];

        file_put_contents('test.txt', PHP_EOL . json_encode($person->getErrors()), FILE_APPEND);
        
        // Если такой ЛК уже есть
        $user = Persons::find()->where(['mail' => $newmail])->andWhere(['>', 'user_id', 0])->one();
        if (!empty($user)) {
            $person->user_id = $user->id;
            $person->serv_info = $person->serv_info.'user уже был';

        // }else{
        //     $person->serv_info = $person->serv_info.'user уже был';
        //     $user = Users::createUser($person);    
        }

        
        

        if ($person->save()) {
            // Отправляем письмо, что мы его зарегистрировали - ДЕЛАЕМ ЭТО ПРИ СОЗДАНИИ ЛК
            // \app\models\Persons::findOne($person->id)->sendMail('reg', 'Спасибо за регистрацию', ['name' => $person->name, 'second_name' => $person->second_name]);

            // Создаем нового юзера (личный кабинет)
            
            // file_put_contents('/home/v/vitalhit/crm.goodrepublic.ru/public_html/web/uploads/checkpost.txt',json_encode($user));
            return $person;
        } else {
            file_put_contents('test.txt', PHP_EOL . json_encode($person->getErrors()), FILE_APPEND);
            return null;
        }
    }


    // Создаем гостя (профиль) при регистрации через СОЦ СЕТЬ
    public function addPerson($user_id, $name, $second_name = false, $company_id, $mail = false, $phone = false)
    {
        file_put_contents('test.txt', PHP_EOL . PHP_EOL . 'Эдд Персон Person.php 272 ', FILE_APPEND);
        file_put_contents('test.txt', PHP_EOL . json_encode($user_id, JSON_UNESCAPED_UNICODE), FILE_APPEND);
        


        // Если такая персона уже есть
        $newmail = str_replace(" ", "", $mail);
        $person = Persons::find()->where(['mail' => $newmail])->andWhere(['>', 'user_id', 0])->one();
        if (!empty($person)) {
            return $person;
        }

        $person = new Persons();
        $person->user_id = $user_id;
        $person->name = $name;
        if (!empty($second_name)) {
            $person->second_name = $second_name;
        } else {
            $person->second_name = '-';
        }
        $person->company_id = $company_id;
        $person->mail = $newmail;
        $person->phone = $phone;

        if ($person->save()) {
            return $person;
        } else {
            return $person->getErrors();
        }
    }


    // Имя гостя для меню
    public function Name()
    {
        $user = Users::findOne(Yii::$app->user->id);
        $person = Persons::findOne($user->person_id);
        if ($person->image) {
            return '<img src="/web/uploads/users/' . $person->image . '" class="img-responsive img-circle w26">' . $person->name . ' ' . $person->second_name;
        } else {
            return '<img src="/web/uploads/users/noimage.jpg" class="img-responsive img-circle w26">' . $person->name . ' ' . $person->second_name;
        }
    }

    // Есть ли Непривязанная к компании персона у этого юзера?
    public static function isPerson()
    {
        $user = Users::findOne(Yii::$app->user->id);
        $person = Persons::findOne($user->person_id);
        return $person;
    }


    public function getVisitsCount($user_id)
    {
        return count(Visits::findAll(['user_id' => $user_id]));
    }

    public function getVisits()
    {
        return $this->hasMany(Visits::className(), ['user_id' => 'id']);
    }

    public function getTicketsCount($user_id)
    {
        return count(Tickets::findAll(['user_id' => $user_id]));
    }

    public function getTickets()
    {
        return $this->hasMany(Tickets::className(), ['user_id' => 'id']);
    }

    public function getSells()
    {
        return $this->hasMany(Sells::className(), ['user_id' => 'id']);
    }

    public function getAbonements()
    {
        return $this->hasMany(Abonements::className(), ['user_id' => 'id']);
    }

    public function getRents()
    {
        return $this->hasMany(Rents::className(), ['person_id' => 'id']);
    }

    public function getFroms()
    {
        return $this->hasOne(Froms::className(), ['id' => 'froms_id']);
    }

    public function getCompanyPerson()
    {
        return $this->hasOne(CompanyPerson::className(), ['person_id' => 'id']);
    }

    public function getcompanyUser()
    {
        return $this->hasOne(CompanyUser::className(), ['user_id' => 'user_id']);
    }

    // public function getUsers()
    //    {
    //        return $this->hasMany(Persons::className(), ['user_id' => 'user_id'])
    //                ->viaTable('company_user', ['company_id' => 'id'])
    //                ->joinwith('companyUser');
    //    }
}
