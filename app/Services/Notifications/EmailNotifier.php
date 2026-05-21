<?php

namespace App\Services\Notifications;

/**
 * Decorator Pattern: EmailNotifier
 * Concrete Component that provides default implementation of the send operation (Email).
 */
class EmailNotifier implements Notifier
{
    public function send(string $message): string
    {
        return "[Email Notifier] Sent email with message: '" . $message . "'";
    }
}
