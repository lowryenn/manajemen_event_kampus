<?php

namespace App\Factories;

/**
 * Factory Pattern: EventInterface
 * Declares operations that all concrete events must implement.
 */
interface EventInterface
{
    /**
     * Get the type of the event (e.g. online, offline).
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get specific layout or details required for this type of event.
     *
     * @return array
     */
    public function getDetails(): array;
}
