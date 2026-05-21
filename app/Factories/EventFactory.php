<?php

namespace App\Factories;

/**
 * Factory Pattern: EventFactory
 * Responsible for creating instances of EventInterface based on input type.
 */
class EventFactory
{
    /**
     * Create an Event object.
     *
     * @param string $type
     * @param string $title
     * @param string $description
     * @param string $location
     * @return EventInterface
     * @throws \InvalidArgumentException
     */
    public static function create(string $type, string $title, string $description, string $location): EventInterface
    {
        switch (strtolower($type)) {
            case 'online':
                return new OnlineEvent($title, $description, $location);
            case 'offline':
                return new OfflineEvent($title, $description, $location);
            default:
                throw new \InvalidArgumentException("Unknown event type: " . $type);
        }
    }
}
