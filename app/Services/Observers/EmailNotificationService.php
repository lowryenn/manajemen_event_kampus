<?php

namespace App\Services\Observers;

use Illuminate\Support\Facades\Log;

/**
 * Observer Pattern: EmailNotificationService
 * Concrete Observer that simulates sending email notifications upon successful registration.
 */
class EmailNotificationService implements ObserverInterface
{
    public function update($registration): void
    {
        // Simple logging or simulation
        $user = $registration->user;
        $event = $registration->event;
        
        $logMessage = "[EmailNotificationService] Sending confirmation email to " . $user->email . 
                      " for event: '" . $event->title . "' (Registration ID: " . $registration->id . ")";
        
        Log::info($logMessage);
    }
}
