<?php

namespace App\Services\Adapters;

/**
 * Adapter Pattern: PaymentGatewayAdapter (Target Interface)
 * Defines the standard payment gateway API wrapper interface used by the application.
 */
interface PaymentGatewayAdapter
{
    /**
     * Standardized method to charge or process a payment.
     *
     * @param int $orderId
     * @param float $amount
     * @return array
     */
    public function processPayment(int $orderId, float $amount): array;
}
