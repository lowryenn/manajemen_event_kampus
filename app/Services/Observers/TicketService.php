<?php

namespace App\Services\Observers;

use Illuminate\Support\Facades\Log;

/**
 * Observer Pattern: TicketService
 * Concrete Observer that simulates ticket generation/issuance upon event registration.
 */
class TicketService implements ObserverInterface
{
    public function update($registration): void
    {
        $user = $registration->user;
        $event = $registration->event;
        $ticketCode = 'TCK-' . strtoupper(substr(md5($registration->id . $event->id), 0, 8));

        $logMessage = "[TicketService] Generating ticket code " . $ticketCode . " for " . $user->name .
                      " (Event: " . $event->name . ")";

        Log::info($logMessage);
    }
}
