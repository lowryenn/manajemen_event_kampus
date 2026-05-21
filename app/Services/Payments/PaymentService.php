<?php

namespace App\Services\Payments;

/**
 * Strategy Pattern: PaymentService (Context)
 * Consumes a PaymentStrategy and executes it dynamically.
 */
class PaymentService
{
    private PaymentStrategy $strategy;

    /**
     * Set the active payment strategy.
     *
     * @param PaymentStrategy $strategy
     */
    public function setStrategy(PaymentStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Execute the payment strategy.
     *
     * @param float $amount
     * @return array
     */
    public function process(float $amount): array
    {
        if (!isset($this->strategy)) {
            throw new \Exception("Payment strategy is not defined.");
        }

        return $this->strategy->pay($amount);
    }
}
