<?php

namespace App\Factories;

/**
 * Factory Pattern: OnlineEvent
 * Concrete product representing events held online (e.g. Zoom, Google Meet).
 */
class OnlineEvent implements EventInterface
{
    private string $title;
    private string $description;
    private string $location; // URL for Online Events

    public function __construct(string $title, string $description, string $location)
    {
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
    }

    public function getType(): string
    {
        return 'online';
    }

    public function getDetails(): array
    {
        return [
            'type' => $this->getType(),
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'platform' => $this->parsePlatform($this->location),
            'instructions' => 'Join the online meeting link at the event time. Link: ' . $this->location
        ];
    }

    private function parsePlatform(string $url): string
    {
        if (stripos($url, 'zoom') !== false) {
            return 'Zoom Meetings';
        } elseif (stripos($url, 'meet.google') !== false) {
            return 'Google Meet';
        }
        return 'Webinar Platform';
    }
}
