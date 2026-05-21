<?php

namespace App\Services\Notifications;

/**
 * Decorator Pattern: WhatsAppNotifierDecorator
 * Concrete Decorator that adds WhatsApp messaging capability to the wrapped notifier.
 */
class WhatsAppNotifierDecorator extends NotifierDecorator
{
    public function send(string $message): string
    {
        // Call the parent send (runs wrapped component behavior) and append WhatsApp notification behavior
        $baseResult = parent::send($message);
        return $baseResult . "\n" . "[WhatsApp Decorator] Sent WhatsApp message: '" . $message . "'";
    }
}
