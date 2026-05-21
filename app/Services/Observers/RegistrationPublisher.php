<?php

namespace App\Services\Observers;

/**
 * Observer Pattern: RegistrationPublisher
 * Concrete Subject that publishes registration events to registered observers.
 */
class RegistrationPublisher implements SubjectInterface
{
    private array $observers = [];

    public function attach(ObserverInterface $observer): void
    {
        $id = spl_object_hash($observer);
        $this->observers[$id] = $observer;
    }

    public function detach(ObserverInterface $observer): void
    {
        $id = spl_object_hash($observer);
        unset($this->observers[$id]);
    }

    public function notify($data): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($data);
        }
    }
}
