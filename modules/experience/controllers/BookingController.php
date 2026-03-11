<?php

namespace app\modules\experience\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\db\Exception;
use app\models\Events;
use app\models\Sessions;
use app\models\ExperienceOrder;
use app\models\Tickets;
use app\models\Companies;
use app\models\Persons;
use app\models\Users;
use app\models\Seats;

/**
 * Контроллер бронирования экскурсий.
 * Отображает виджет и обрабатывает AJAX-запросы.
 */
class BookingController extends Controller
{
    public $enableCsrfValidation = true;

    /**
     * Страница с виджетом бронирования
     */
    public function actionIndex($id)
    {
        $this->layout = '@app/views/layouts/site';
        Yii::$app->view->title = 'Бронирование';

        return $this->render('index', [
            'biblioevent_id' => $id,
            'date' => Yii::$app->request->get('date'),
        ]);
    }

    /**
     * Получить доступные сеансы для даты (AJAX)
     */
    public function actionGetSessions()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $eventId = Yii::$app->request->post('event_id');
        $date = Yii::$app->request->post('date');
        $biblioeventId = Yii::$app->request->post('biblioevent_id');

        if (!$eventId && $date && $biblioeventId) {
            $event = Events::find()
                ->where(['event_id' => $biblioeventId, 'status' => 1])
                ->andWhere('DATE(date) = :date', [':date' => $date])
                ->one();
            $eventId = $event ? $event->id : null;
        }

        if (!$eventId) {
            return ['success' => false, 'message' => 'Дата не найдена'];
        }

        $sessions = Sessions::find()
            ->where(['event_id' => $eventId, 'status' => 1])
            ->andWhere('(max_tickets - booked_tickets) > 0')
            ->all();

        $result = [];
        foreach ($sessions as $session) {
            $result[] = [
                'id' => $session->id,
                'time_start' => $session->time_start,
                'time_end' => $session->time_end,
                'available' => $session->getAvailableTickets(),
                'max' => $session->max_tickets,
                'price_multiplier' => (float) $session->price_multiplier,
            ];
        }

        return ['success' => true, 'sessions' => $result, 'event_id' => $eventId];
    }

    /**
     * Создание бронирования (POST)
     */
    public function actionCreateBooking()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $data = Yii::$app->request->post();

            $sessionId = $data['session_id'] ?? null;
            if (!$sessionId) {
                throw new \Exception('Сеанс не указан');
            }

            $session = Sessions::findOne($sessionId);
            if (!$session || !$session->isAvailable()) {
                throw new \Exception('Сеанс не найден');
            }

            $ticketsRaw = $data['tickets'] ?? '[]';
            $ticketsData = is_string($ticketsRaw) ? json_decode($ticketsRaw, true) : $ticketsRaw;
            $ticketsData = is_array($ticketsData) ? $ticketsData : [];
            $totalQuantity = 0;
            foreach ($ticketsData as $t) {
                $totalQuantity += (int) ($t['quantity'] ?? 0);
            }

            if ($totalQuantity <= 0) {
                throw new \Exception('Выберите количество билетов');
            }

            if ($session->getAvailableTickets() < $totalQuantity) {
                throw new \Exception('Недостаточно свободных мест');
            }

            $event = $session->event;
            if (!$event) {
                throw new \Exception('Событие не найдено');
            }

            $biblioevent = $event->biblioevent ?? $event->biblioevents;
            if (!$biblioevent) {
                throw new \Exception('Экскурсия не найдена');
            }
            if (empty($biblioevent->company_id)) {
                throw new \Exception('У экскурсии не указана компания');
            }

            $company = Companies::findOne($biblioevent->company_id);
            $prepaymentPercent = ($company && isset($company->percent)) ? (int) $company->percent : 25;

            $multiplier = (float) ($session->price_multiplier ?? 1);
            $totalAmount = 0;
            foreach ($ticketsData as $t) {
                $qty = (int) ($t['quantity'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }
                $seatId = (int) ($t['seat_id'] ?? 0);
                if ($seatId <= 0) {
                    throw new \Exception('Тип билета не указан');
                }
                $seat = Seats::find()
                    ->where(['id' => $seatId, 'biblioevent_id' => $biblioevent->id, 'is_active' => 1])
                    ->one();
                if (!$seat) {
                    throw new \Exception('Тип билета не найден или недоступен');
                }
                $totalAmount += (float) $seat->price * $qty * $multiplier;
            }
            $prepaymentAmount = $totalAmount * $prepaymentPercent / 100;

            $order = new ExperienceOrder();
            $order->company_id = $biblioevent->company_id;
            $order->total_amount = $totalAmount;
            $order->prepayment_amount = $prepaymentAmount;
            $order->prepayment_percent = $prepaymentPercent;
            $order->status = ExperienceOrder::STATUS_WAITING;

            if (!$order->save()) {
                throw new \Exception('Ошибка создания заказа: ' . json_encode($order->errors));
            }

            $orderNumber = $order->order_number;
            $customerEmail = trim($data['customer_email'] ?? '');
            $customerName = trim($data['customer_name'] ?? '');
            $customerPhone = trim($data['customer_phone'] ?? '');
            $fromUrl = $data['from_url'] ?? '';

            $person = null;
            $user = null;
            $userCreated = false;
            if ($customerEmail) {
                $person = Persons::find()->where(['mail' => $customerEmail])->one();
                if (!$person) {
                    $person = new Persons();
                    $parts = preg_split('/\s+/u', trim($customerName), 2);
                    $person->second_name = $parts[0] ?? '-';
                    $person->name = $parts[1] ?? $parts[0] ?? '-';
                    $person->mail = $customerEmail;
                    $person->phone = $customerPhone;
                    $person->company_id = $biblioevent->company_id;
                    if (!$person->save()) {
                        throw new \Exception('Ошибка создания профиля: ' . json_encode($person->errors));
                    }
                }
                $userExisted = Users::find()->where(['email' => $person->mail])->andWhere(['>', 'status', 0])->exists();
                $user = Users::createUser($person);
                if ($user && !$person->user_id) {
                    $person->user_id = $user->id;
                    $person->save(false);
                }
                $person->refresh();
                if (!$userExisted && $person->user_id && $user) {
                    $userCreated = true;
                }
            }
            $userId = $person ? $person->id : 0;
            $userField = $person && $person->user_id ? $person->user_id : 0;

            foreach ($ticketsData as $t) {
                $qty = (int) ($t['quantity'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }

                $seatId = (int) ($t['seat_id'] ?? 0);
                if ($seatId <= 0) {
                    throw new \Exception('Тип билета не указан');
                }
                $seat = Seats::find()
                    ->where(['id' => $seatId, 'biblioevent_id' => $biblioevent->id, 'is_active' => 1])
                    ->one();
                if (!$seat) {
                    throw new \Exception('Тип билета не найден или недоступен');
                }

                $price = (float) $seat->price;
                $multiplier = (float) ($session->price_multiplier ?? 1);
                $finalPrice = $price * $multiplier;

                for ($i = 0; $i < $qty; $i++) {
                    $ticket = new Tickets();
                    $ticket->order_id = $orderNumber;
                    $ticket->experience_order_id = $order->id;
                    $ticket->session_id = $session->id;
                    $ticket->event_id = $event->id;
                    $ticket->seat_id = $seatId > 0 ? $seatId : null;
                    $ticket->ticket_category_id = 0;
                    $ticket->quantity = 1;
                    $ticket->price = $finalPrice;
                    $ticket->summa = round($finalPrice);
                    $ticket->money = round($finalPrice);
                    $ticket->count = 1;
                    $ticket->name = $customerName;
                    $ticket->email = $customerEmail;
                    $ticket->phone = $customerPhone;
                    $ticket->customer_name = $customerName;
                    $ticket->customer_email = $customerEmail;
                    $ticket->customer_phone = $customerPhone;
                    $ticket->info = $data['comment'] ?? '';
                    $ticket->company_id = $biblioevent->company_id;
                    $ticket->biblioevent_id = $biblioevent->id;
                    $ticket->user_id = $userId;
                    $ticket->user = $userField;
                    $ticket->person_id = $userId;
                    $ticket->type = 0;  // 0 = Не оплачено (способ оплаты)
                    $ticket->date = date('Y-m-d H:i:s');
                    $ticket->status = ExperienceOrder::STATUS_WAITING;
                    $ticket->promocode = '';
                    $ticket->del = 0;
                    $ticket->canceled = null;
                    $ticket->from_url = $fromUrl;
                    $ticket->subscribe = 1;
                    $ticket->duty = 0;

                    if (!$ticket->save(false)) {
                        throw new \Exception('Ошибка создания билета: ' . json_encode($ticket->errors));
                    }

                    $ticket->barcode = str_pad(substr((string)$ticket->event_id, 0, 5), 5, '0', STR_PAD_RIGHT) . substr($ticket->date, 17, 2) . str_pad($ticket->id, 6, '0', STR_PAD_LEFT);
                    $ticket->save(false);
                }
            }

            $session->booked_tickets += $totalQuantity;
            $session->last_calc_at = date('Y-m-d H:i:s');
            $session->save(false);

            $transaction->commit();

            if ($userCreated && $user && $customerEmail) {
                $supportEmail = Yii::$app->params['supportEmail'] ?? 'v@igoevent.com';
                Yii::$app->mailer->compose(
                    ['html' => 'lkCreate-html', 'text' => 'lkCreate-text'],
                    ['user' => $user]
                )
                    ->setFrom([$supportEmail => 'Igoevent.com'])
                    ->setTo($customerEmail)
                    ->setSubject('Создан личный кабинет на Igoevent.com')
                    ->send();
            }

            return [
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'payment_url' => 'https://igoevent.com/site/pay?order_id=' . urlencode($orderNumber),
                'prepayment_amount' => $prepaymentAmount,
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Проверка доступности мест (AJAX)
     */
    public function actionCheckAvailability()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $sessionId = Yii::$app->request->post('session_id');
        $quantity = (int) Yii::$app->request->post('quantity', 0);

        $session = Sessions::findOne($sessionId);
        if (!$session) {
            return ['available' => false, 'message' => 'Сеанс не найден'];
        }

        return [
            'available' => $session->getAvailableTickets() >= $quantity,
            'available_tickets' => $session->getAvailableTickets(),
            'booked_tickets' => $session->booked_tickets,
            'max_tickets' => $session->max_tickets,
        ];
    }
}
