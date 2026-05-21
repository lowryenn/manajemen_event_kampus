<?php

namespace App\Services\Notifications;

/**
 * Decorator Pattern: Notifier Interface
 * Component interface declaring the message sending operation.
 */
interface Notifier
{
    /**
     * Send a notification message.
     *
     * @param string $message
     * @return string Log/Result of the notifications sent
     */
    public function send(string $message): string;
}
