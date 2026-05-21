<?php

namespace App\Services\Observers;

/**
 * Observer Pattern: SubjectInterface
 * Interface for the publisher (Subject) of events.
 */
interface SubjectInterface
{
    public function attach(ObserverInterface $observer): void;
    public function detach(ObserverInterface $observer): void;
    public function notify($data): void;
}
