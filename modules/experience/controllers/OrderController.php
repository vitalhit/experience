<?php

namespace app\modules\experience\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Tickets;
use app\models\Events;
use app\models\Biblioevents;
use app\models\Persons;
use app\models\Companies;

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
            echo 'test'; die;
        if (empty($tickets)) {
            throw new NotFoundHttpException('Заказ не найден.');
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
        
        $this->layout = 'site';
        Yii::$app->view->title = 'Заказ — ' . $biblioevent->name;

        $paymentUrl = 'https://igoevent.com/site/pay?order_id=' . urlencode($token);

        $dateFormatted = $event->date ? Yii::$app->formatter->asDate($event->date, 'php:j F, D') : '';
        $timeFormatted = $event->date ? Yii::$app->formatter->asTime($event->date, 'php:H:i') : '';
        $datetimeFormatted = $event->date ? Yii::$app->formatter->asDatetime($event->date, 'php:d F, D, H:i') : '';

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
