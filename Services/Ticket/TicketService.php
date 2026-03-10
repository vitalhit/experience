<?php

namespace app\Services\Ticket;

class TicketService
{
    public static function summa(array $tickets): float
    {
        $summa = 0.0;
        foreach ($tickets as $ticket) {
            $summa += $ticket->summa;
        }

        return sprintf("%.2f", $summa);
    }
}
