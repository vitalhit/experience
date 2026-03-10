<?php

namespace app\modules\experience\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\helpers\ArrayHelper;
use app\models\Tickets;
use app\models\Events;
use app\models\Biblioevents;
use app\models\Persons;
use app\models\Companies;
use app\models\Comment;

/**
 * Контроллер заказов экскурсий. Страница заказа по token (order_id).
 * Оплата — переход на https://igoevent.com/site/pay?order_id={token}
 */
class OrderController extends Controller
{
    /** Статусы билетов: 0 — ожидает оплаты, 1 — регистрация (нулевая стоимость), 5 — оплачен, 7 — возврат */
    const STATUS_PENDING = 0;
    const STATUS_REGISTRATION = 1;
    const STATUS_PAID = 5;
    const STATUS_REFUND = 7;

    /**
     * Страница заказа по token (order_id из tickets).
     * @param string $token order_id, например t69a98d1da16ce
     */
    public function actionView($token)
    {
        $tickets = Tickets::find()
            ->joinWith('events')
            ->joinWith('events.biblioevents')
            ->joinWith('seats')
            ->where(['tickets.order_id' => $token])
            ->andWhere(['tickets.del' => 0])
            ->all();
            
        if (empty($tickets)) {
            throw new NotFoundHttpException('Заказ не найден.');
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }
        $persons = Persons::find()->where(['persons.user_id' => Yii::$app->user->id])->all();
        $pids = ArrayHelper::getColumn($persons, 'id');
        if (empty($pids) || !in_array((int) $tickets[0]->user_id, array_map('intval', $pids), true)) {
            throw new ForbiddenHttpException('Нет доступа к этому заказу.');
        }

        $event = Events::find()
            ->joinWith('biblioevents')
            ->andWhere(['events.id' => $tickets[0]->event_id])
            ->one();

        if (!$event || !$event->biblioevents) {
            throw new NotFoundHttpException('Данные события не найдены.');
        }

        $biblioevent = $event->biblioevents;
        $sum = (int) Tickets::find()
            ->where(['order_id' => $token])
            ->andWhere(['tickets.del' => 0])
            ->sum('summa');

        $statusLabel = $this->getStatusLabel($tickets);
        $canPay = $this->canPay($tickets, $sum);

        $participantsCount = 0;
        foreach ($tickets as $t) {
            $participantsCount += (int) ($t->count ?? 1);
        }

        $user = Persons::findOne($tickets[0]->user_id);
        $guideName = null;
        $guideContact = null;
        if ($biblioevent->company_id) {
            $company = Companies::findOne($biblioevent->company_id);
            if ($company) {
                $guideName = $company->name ?? 'Организатор';
            }
        }
        if (!$guideName) {
            $guideName = 'Организатор';
        }
        
        $this->layout = '@app/views/layouts/site';
        Yii::$app->view->title = 'Заказ — ' . $biblioevent->name;

        $paymentUrl = 'https://igoevent.com/site/pay?order_id=' . urlencode($token);

        $dateFormatted = $event->date ? Yii::$app->formatter->asDate($event->date, 'php:j F, D') : '';
        $timeFormatted = $event->date ? Yii::$app->formatter->asTime($event->date, 'php:H:i') : '';
        $datetimeFormatted = $event->date ? Yii::$app->formatter->asDatetime($event->date, 'php:d F, D, H:i') : '';

        $comments = Comment::find()
            ->where(['order_id' => $token])
            ->andWhere(['or', ['deleted' => 0], ['deleted' => null]])
            ->joinWith('author')
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        $addCommentUrl = Yii::$app->urlManager->createUrl(['/experience/order/add-comment', 'token' => $token]);

        return $this->render('view.twig', [
            'tickets' => $tickets,
            'event' => $event,
            'biblioevent' => $biblioevent,
            'sum' => $sum,
            'order_id' => $token,
            'statusLabel' => $statusLabel,
            'canPay' => $canPay,
            'participantsCount' => $participantsCount,
            'user' => $user,
            'guideName' => $guideName,
            'guideContact' => $guideContact,
            'paymentUrl' => $paymentUrl,
            'dateFormatted' => $dateFormatted,
            'timeFormatted' => $timeFormatted,
            'datetimeFormatted' => $datetimeFormatted,
            'meetingPlace' => trim($event->place ?? '') ?: trim($event->address ?? '') ?: null,
            'comments' => $comments,
            'addCommentUrl' => $addCommentUrl,
            'csrfParam' => Yii::$app->request->csrfParam,
            'csrfToken' => Yii::$app->request->getCsrfToken(),
        ]);
    }

    /**
     * Добавление комментария к заказу (POST).
     * @param string $token order_id
     */
    public function actionAddComment($token)
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            return $this->redirect(['view', 'token' => $token]);
        }

        $tickets = Tickets::find()
            ->joinWith('events')
            ->joinWith('events.biblioevents')
            ->where(['tickets.order_id' => $token])
            ->andWhere(['tickets.del' => 0])
            ->all();
        if (empty($tickets)) {
            throw new NotFoundHttpException('Заказ не найден.');
        }

        $event = Events::find()->joinWith('biblioevents')->andWhere(['events.id' => $tickets[0]->event_id])->one();
        $biblioevent = $event && $event->biblioevents ? $event->biblioevents : null;

        $body = trim((string) ($request->post('body') ?? ''));
        if ($body === '' || mb_strlen($body) > 1000) {
            Yii::$app->session->setFlash('danger', 'Текст комментария обязателен (до 1000 символов).');
            return $this->redirect(['view', 'token' => $token]);
        }

        $authorId = null;
        $authorType = Comment::AUTHOR_TYPE_GUEST;

        if (!Yii::$app->user->isGuest) {
            $person = Persons::findOne(['user_id' => Yii::$app->user->id]);
            if ($person) {
                $authorId = $person->id;
                if (Yii::$app->authManager->getAssignment('manager', Yii::$app->user->id)
                    || Yii::$app->authManager->getAssignment('admin', Yii::$app->user->id)) {
                    $authorType = Comment::AUTHOR_TYPE_MANAGER;
                } elseif ($biblioevent && (int) $person->company_id === (int) $biblioevent->company_id) {
                    $authorType = Comment::AUTHOR_TYPE_GUIDE;
                } else {
                    $authorType = Comment::AUTHOR_TYPE_GUEST;
                }
            }
        }

        $comment = new Comment();
        $comment->order_id = $token;
        $comment->body = $body;
        $comment->author_id = $authorId;
        $comment->author_type = $authorType;
        $comment->id_event = $event ? $event->id : null;
        $comment->id_biblioevent = $biblioevent ? $biblioevent->id : null;
        $comment->deleted = 0;
        if (!$comment->save()) {
            Yii::$app->session->setFlash('danger', 'Не удалось сохранить комментарий.');
            return $this->redirect(['view', 'token' => $token]);
        }

        Yii::$app->session->setFlash('success', 'Комментарий добавлен.');
        return $this->redirect(['view', 'token' => $token]);
    }

    /**
     * Список заказов текущего пользователя (по order_id из tickets) с суммами и статусами.
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }
        $persons = Persons::find()->where(['persons.user_id' => Yii::$app->user->id])->all();
        $pids = ArrayHelper::getColumn($persons, 'id');

        if (empty($pids)) {
            $tickets = [];
        } else {
            $tickets = Tickets::find()
                ->joinWith('events')
                ->joinWith('events.biblioevents')
                ->where(['tickets.del' => 0])
                ->andWhere(['tickets.user_id' => $pids])
                ->orderBy(['tickets.date' => SORT_DESC])
                ->limit(2000)
                ->all();
        }

        $byOrder = [];
        foreach ($tickets as $ticket) {
            $oid = $ticket->order_id;
            if (!isset($byOrder[$oid])) {
                $byOrder[$oid] = [
                    'order_id' => $oid,
                    'tickets' => [],
                    'sum' => 0,
                    'event' => null,
                    'biblioevent' => null,
                ];
            }
            $byOrder[$oid]['tickets'][] = $ticket;
            $byOrder[$oid]['sum'] += (int) $ticket->summa;
            if ($byOrder[$oid]['event'] === null && $ticket->events) {
                $byOrder[$oid]['event'] = $ticket->events;
                $byOrder[$oid]['biblioevent'] = $ticket->events->biblioevents ?? null;
            }
        }

        $orders = [];
        foreach ($byOrder as $row) {
            $eventDate = null;
            if (!empty($row['event']) && !empty($row['event']->date)) {
                $eventDate = is_numeric($row['event']->date) ? $row['event']->date : strtotime($row['event']->date);
            }
            $orderDate = !empty($row['tickets'][0]) ? $row['tickets'][0]->date : null;
            $orders[] = [
                'order_id' => $row['order_id'],
                'statusLabel' => $this->getStatusLabel($row['tickets']),
                'sum' => $row['sum'],
                'event' => $row['event'],
                'biblioevent' => $row['biblioevent'],
                'date' => $orderDate,
                'eventDate' => $eventDate,
                'eventDateFormatted' => $eventDate !== null ? date('d.m.Y H:i', $eventDate) : null,
            ];
        }

        $now = time();
        $ordersFuture = [];
        $ordersPast = [];
        foreach ($orders as $order) {
            $ed = $order['eventDate'];
            if ($ed !== null && $ed >= $now) {
                $ordersFuture[] = $order;
            } else {
                $ordersPast[] = $order;
            }
        }
        usort($ordersFuture, function ($a, $b) {
            $da = $a['eventDate'] ?? 0;
            $db = $b['eventDate'] ?? 0;
            return $da <=> $db;
        });
        usort($ordersPast, function ($a, $b) {
            $da = $a['eventDate'] ?? 0;
            $db = $b['eventDate'] ?? 0;
            return $db <=> $da;
        });

        $this->layout = '@app/views/layouts/site';
        Yii::$app->view->title = 'Список заказов';

        return $this->render('index.twig', [
            'ordersFuture' => $ordersFuture,
            'ordersPast' => $ordersPast,
        ]);
    }

    /**
     * Текстовый статус заказа по статусам билетов.
     * @param Tickets[] $tickets
     * @return string
     */
    private function getStatusLabel(array $tickets): string
    {
        $statuses = array_unique(array_map(function ($t) {
            return (int) $t->status;
        }, $tickets));

        if (in_array(self::STATUS_REFUND, $statuses, true)) {
            return 'Возврат';
        }
        if (in_array(self::STATUS_PAID, $statuses, true) && !in_array(self::STATUS_PENDING, $statuses, true)) {
            return 'Оплачен';
        }
        if (in_array(self::STATUS_REGISTRATION, $statuses, true) && count($statuses) === 1) {
            return 'Регистрация';
        }
        if (in_array(self::STATUS_PENDING, $statuses, true)) {
            return 'Заказ подтвержден и ожидает оплаты';
        }

        return 'Заказ';
    }

    /**
     * Можно ли показать кнопку оплаты (есть неоплаченные билеты с суммой > 0).
     * @param Tickets[] $tickets
     * @param int $sum
     * @return bool
     */
    private function canPay(array $tickets, int $sum): bool
    {
        if ($sum <= 0) {
            return false;
        }
        foreach ($tickets as $t) {
            if ((int) $t->status === self::STATUS_PENDING) {
                return true;
            }
        }
        return false;
    }
}
