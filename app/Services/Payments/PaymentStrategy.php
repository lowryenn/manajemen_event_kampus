<?php

namespace App\Services\Payments;

/**
 * Strategy Pattern: PaymentStrategy Interface
 * Defines the contract for all payment processing strategies.
 */
interface PaymentStrategy
{
    /**
     * Process a payment for a specific amount.
     *
     * @param float $amount
     * @return array Contains payment status, message, and transaction details
     */
    public function pay(float $amount): array;
}
