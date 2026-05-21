<?php

namespace App\Services\Notifications;

/**
 * Decorator Pattern: NotifierDecorator (Base Decorator)
 * Conforms to Notifier and delegates all work to the wrapped Notifier component.
 */
abstract class NotifierDecorator implements Notifier
{
    protected Notifier $wrappedNotifier;

    public function __construct(Notifier $notifier)
    {
        $this->wrappedNotifier = $notifier;
    }

    public function send(string $message): string
    {
        return $this->wrappedNotifier->send($message);
    }
}
