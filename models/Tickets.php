<?php

namespace app\models;

/**
 * This is the model class for table "tickets".
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $user
 * @property string $name
 * @property string $secondname
 * @property string $email
 * @property string $phone
 * @property integer $smena_id
 * @property string $mark
 * @property string $order_id
 * @property integer $user_id
 * @property integer $person_id
 * @property integer $client_id
 * @property integer $biblioevent_id
 * @property integer $event_id
 * @property integer $seat_id
 * @property integer $seat
 * @property integer $money
 * @property integer $count
 * @property integer $summa
 * @property string $date
 * @property string $type
 * @property string $info
 * @property string $admin
 * @property string $from_url
 * @property integer $subscribe
 * @property string $field1
 * @property string $field2
 * @property string $field3
 * @property string $field4
 * @property string $field5
 * @property string $field6
 * @property string $field7
 * @property string $field8
 * @property string $field9
 * @property string $field10
 * @property string $promocode
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property integer $status 0 = бронь снята
 * @property integer $send
 * @property integer $status_come
 * @property string $barcode
 * @property integer $template_id
 * @property integer $del 1 = удален
 * @property integer $canceled 1 = отменен
 * @property integer $session_id
 * @property integer $ticket_category_id
 * @property integer $quantity
 * @property float $price
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property string $comment
 * @property integer $experience_order_id
 */
class Tickets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tickets';
    }

    public $nal;
    public $beznal;
    public $yandex;


    static function pluralForm($n, $form1, $form2, $form5)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $form5;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;
        return $form5;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'money', 'type'], 'required'],
            [['company_id', 'user', 'person_id', 'smena_id', 'user_id', 'client_id', 'biblioevent_id', 'event_id', 'seat_id', 'seat', 'money', 'count', 'summa', 'type', 'subscribe', 'status', 'send', 'status_come', 'template_id', 'del', 'canceled', 'session_id', 'ticket_category_id', 'quantity', 'experience_order_id'], 'integer'],
            [['price'], 'number'],
            [['date'], 'safe'],
            [['name', 'secondname', 'email', 'phone','order_id', 'info', 'admin', 'from_url', 'field1', 'field2', 'field3', 'field4', 'field5', 'field6', 'field7', 'field8', 'field9', 'field10', 'promocode', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'barcode', 'mark', 'customer_name', 'customer_email', 'customer_phone', 'comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'smena_id' => 'Номер смены',
            'order_id' => 'Номер заказа',
            'mark' => 'Пометка организатора',
            'user_id' => 'User ID',
            'biblioevent_id' => 'Событие',
            'event_id' => 'Дата',
            'seat_id' => 'Тип места',
            'seat' => 'Номер места',
            'date' => 'Дата',
            'money' => 'Цена 1 билета',
            'count' => 'Кол-во билетов',
            'summa' => 'Сумма',
            'type' => 'Способ оплаты',
            'info' => 'Комментарий пользователя',
            'admin' => 'Служебная информация',
            'from_url' => 'Сайт где куплен билет',
            'subscribe' => 'Подписка на новости',
            'field1' => 'Название поля 1: ник',
            'field2' => 'Название поля 2: ВК',
            'field3' => 'Название поля 3: ',
            'field4' => 'Название поля 4',
            'field5' => 'Название поля 5',
            'field6' => 'Название поля 6',
            'field7' => 'Название поля 7',
            'field8' => 'Название поля 8',
            'field9' => 'Название поля 9',
            'field10' => 'Название поля 10',
            'promocode' => 'Промо-код',
            'utm_source' => 'utm_source',
            'utm_medium' => 'utm_medium',
            'utm_campaign' => 'utm_campaign',
            'utm_content' => 'utm_content',
            'utm_term' => 'utm_term',
            'status' => 'Статус оплаты',
            'send' => 'Отправка письма',
            'status_come' => 'Пришел - не пришел',
            'barcode' => 'Штрихкод',
            'template_id' => 'Template Id',
            'del' => 'Удален',
            'canceled' => "Анулировать билет"
        ];
    }

    // Доход от продажи билетов
    public static function Dohod($company, $biblioevent = false, $month = false, $year = false)
    {
        if (!empty($biblioevent)) {
            $event_ids = Biblioevents::EventsIds($biblioevent);
        } else {
            $event_ids = Biblioevents::EventsIdsAll($company);
        }

        if (empty($month)) {
            $month = DATE('m');
        }
        if (empty($year)) {
            $year = DATE('Y');
        }

        $t_pay_c = Tickets::find()->where(['event_id' => $event_ids, 'status' => 5])->andWhere(['MONTH(`date`)' => $month, 'YEAR(`date`)' => $year])->count();
        $t_pay_s = Tickets::find()->where(['event_id' => $event_ids, 'status' => 5])->andWhere(['MONTH(`date`)' => $month, 'YEAR(`date`)' => $year])->sum('summa');

        if (!empty($biblioevent)) {
            if (!empty($t_pay_s)) {
                return array('biblioevent' => $biblioevent, 'count' => $t_pay_c, 'summa' => $t_pay_s);
            }
        } else {
            if (!empty($t_pay_s)) {
                return array('count' => $t_pay_c, 'summa' => $t_pay_s);
            }
        }
    }


    // Возвраты билетов
    public function Back($company, $month = false, $year = false)
    {
        $event_ids = Biblioevents::EventsIds($company);

        if (empty($month)) {
            $month = DATE('m');
        }
        if (empty($year)) {
            $year = DATE('Y');
        }

        $t_back_c = Tickets::find()->where(['event_id' => $event_ids])->andWhere('status = 7')->andWhere(['MONTH(`date`)' => $month, 'YEAR(`date`)' => $year])->count();
        $t_back_s = Tickets::find()->where(['event_id' => $event_ids])->andWhere('status = 7')->andWhere(['MONTH(`date`)' => $month, 'YEAR(`date`)' => $year])->sum('summa');

        $t_pay_c = Tickets::find()->where(['event_id' => $event_ids])->andWhere('status = 5')->andWhere(['MONTH(`date`)' => $month, 'YEAR(`date`)' => $year])->count();
        $t_pay_s = Tickets::find()->where(['event_id' => $event_ids])->andWhere('status = 5')->andWhere(['MONTH(`date`)' => $month, 'YEAR(`date`)' => $year])->sum('summa');

        $payments = CompanyPayments::find()->where(['company_id' => $company->id, 'MONTH(`create_at`)' => $month, 'YEAR(`create_at`)' => $year])->all();
        $payments_s = CompanyPayments::find()->where(['company_id' => $company->id, 'MONTH(`create_at`)' => $month, 'YEAR(`create_at`)' => $year])->sum('summa');

        $all_tickets = Tickets::find()->where(['event_id' => $event_ids])->andWhere('status = 5')->sum('summa');
        $all_payments = CompanyPayments::find()->where(['company_id' => $company->id])->sum('summa');

        $docs = null;
        $dogovordate = null;
        $status = null;

        $result[] = array('t_back_c' => $t_back_c, 't_back_s' => $t_back_s, 't_pay_c' => $t_pay_c, 't_pay_s' => $t_pay_s, 'payments' => $payments, 'payments_s' => $payments_s, 'balance' => $all_tickets - ($all_tickets * 0.06) - $all_payments, 'docs' => $docs, 'dogovordate' => $dogovordate, 'status' => $status);

        $dohod = null;
        return $dohod;
    }

    public static function Sum($e_ids, $status, $start, $end)
    {
        return Tickets::find()->where(['event_id' => $e_ids, 'status' => $status])->andWhere(['between', 'date', $start, $end])->sum('summa');
    }


    public function getPersons()
    {
        return $this->hasOne(Persons::className(), ['id' => 'user_id']);
    }

    public function getEvents()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    public function getSeats()
    {
        return $this->hasOne(Seats::className(), ['id' => 'seat_id']);
    }

    public function getBiblioevent()
    {
        return $this->hasOne(Biblioevents::className(), ['id' => 'event_id'])->viaTable('events', ['id' => 'event_id']);
    }

    public function getExperienceOrder()
    {
        return $this->hasOne(ExperienceOrder::className(), ['id' => 'experience_order_id']);
    }

    public function getSession()
    {
        return $this->hasOne(Sessions::className(), ['id' => 'session_id']);
    }

    public function getTicketCategory()
    {
        return $this->hasOne(TicketCategories::className(), ['id' => 'ticket_category_id']);
    }
}
