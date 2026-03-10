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

            $totalAmount = 0;
            foreach ($ticketsData as $t) {
                $qty = (int) ($t['quantity'] ?? 0);
                if ($qty > 0) {
                    $price = (float) ($t['price'] ?? 0);
                    $multiplier = (float) ($session->price_multiplier ?? 1);
                    $totalAmount += $price * $qty * $multiplier;
                }
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

            foreach ($ticketsData as $t) {
                $qty = (int) ($t['quantity'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }

                $price = (float) ($t['price'] ?? 0);
                $multiplier = (float) ($session->price_multiplier ?? 1);
                $finalPrice = $price * $multiplier;

                $ticket = new Tickets();
                $ticket->order_id = $orderNumber;
                $ticket->experience_order_id = $order->id;
                $ticket->session_id = $session->id;
                $ticket->event_id = $event->id;
                $ticket->ticket_category_id = (int) ($t['category_id'] ?? 0);
                $ticket->quantity = $qty;
                $ticket->price = $finalPrice;
                $ticket->summa = round($finalPrice * $qty);
                $ticket->money = round($finalPrice);
                $ticket->count = $qty;
                $ticket->customer_name = $data['customer_name'] ?? '';
                $ticket->customer_email = $data['customer_email'] ?? '';
                $ticket->customer_phone = $data['customer_phone'] ?? '';
                $ticket->comment = $data['comment'] ?? '';
                $ticket->name = $data['customer_name'] ?? '';
                $ticket->email = $data['customer_email'] ?? '';
                $ticket->phone = $data['customer_phone'] ?? '';
                $ticket->info = $data['comment'] ?? '';
                $ticket->company_id = $biblioevent->company_id;
                $ticket->biblioevent_id = $biblioevent->id;
                $ticket->user_id = 0;
                $ticket->type = 'excursion';
                $ticket->date = date('Y-m-d H:i:s');
                $ticket->status = ExperienceOrder::STATUS_WAITING;
                $ticket->promocode = '';
                $ticket->del = 0;
                $ticket->canceled = 0;

                if (!$ticket->save(false)) {
                    throw new \Exception('Ошибка создания билета: ' . json_encode($ticket->errors));
                }
            }

            $session->booked_tickets += $totalQuantity;
            $session->last_calc_at = date('Y-m-d H:i:s');
            $session->save(false);

            $transaction->commit();

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
