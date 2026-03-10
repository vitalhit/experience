<?php

namespace app\Repository;

use app\models\Tickets;

class TicketRepository
{

    // по orderId
    public static function byOrderId(string $orderId): ?array
    {
        return Tickets::find()->where(['tickets.order_id' => $orderId])->joinWith('seats')->all();
    }


    // YOO KASSA

    // билеты преобразованные для yandex kassa
    public static function ticketsForYoo(array $tickets): ?array
    {
        $items = [];
        foreach ($tickets as $ticket) {
            $description = 'Билет';
            if (!empty($ticket->seats->name)) {
                $description = $ticket->seats->name;
            }

            $items[] = array(
                'description' => $description,
                'quantity' => '1.00',
                'amount' => array(
                    'value' => $ticket->summa,
                    'currency' => 'RUB'
                ),
                'vat_code' => '1',
                'payment_mode' => 'full_payment',
            );
        }
        return $items;
    }

}
