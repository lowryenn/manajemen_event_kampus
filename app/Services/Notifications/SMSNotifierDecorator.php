<?php

namespace App\Services\Notifications;

/**
 * Decorator Pattern: SMSNotifierDecorator
 * Concrete Decorator that adds SMS messaging capability to the wrapped notifier.
 */
class SMSNotifierDecorator extends NotifierDecorator
{
    public function send(string $message): string
    {
        // Call the parent send (runs wrapped component behavior) and append SMS notification behavior
        $baseResult = parent::send($message);
        return $baseResult . "\n" . "[SMS Decorator] Sent SMS message: '" . $message . "'";
    }
}
