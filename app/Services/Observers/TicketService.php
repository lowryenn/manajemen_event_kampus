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
                      " (Event: " . $event->title . ")";
                      
        Log::info($logMessage);
        
        // Save execution log to session for demonstration purposes
        $demoLogs = session()->get('design_patterns_logs', []);
        $demoLogs[] = $logMessage;
        session()->put('design_patterns_logs', $demoLogs);
    }
}
