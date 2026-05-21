<?php

namespace App\Services\Observers;

/**
 * Observer Pattern: ObserverInterface
 * Interface for subscribers (Observers) that need to be notified of publisher changes.
 */
interface ObserverInterface
{
    /**
     * Receive notification from Subject.
     *
     * @param mixed $data
     * @return void
     */
    public function update($data): void;
}
