<?php

namespace App\Factories;

/**
 * Factory Pattern: OfflineEvent
 * Concrete product representing events held physically in a campus building or auditorium.
 */
class OfflineEvent implements EventInterface
{
    private string $title;
    private string $description;
    private string $location; // Physical location (room, building, etc.)

    public function __construct(string $title, string $description, string $location)
    {
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
    }

    public function getType(): string
    {
        return 'offline';
    }

    public function getDetails(): array
    {
        return [
            'type' => $this->getType(),
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'instructions' => 'Please arrive at the physical venue: ' . $this->location . ' at least 15 minutes before the event starts.'
        ];
    }
}
