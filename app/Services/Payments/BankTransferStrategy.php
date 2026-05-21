<?php

namespace App\Services\Payments;

/**
 * Strategy Pattern: BankTransferStrategy
 * Concrete strategy for processing payments via bank transfer.
 */
class BankTransferStrategy implements PaymentStrategy
{
    private string $bankName;
    private string $accountNumber;

    public function __construct(string $bankName = 'MANDIRI', string $accountNumber = '123-456-7890')
    {
        $this->bankName = $bankName;
        $this->accountNumber = $accountNumber;
    }

    public function pay(float $amount): array
    {
        // Simple dummy bank transfer implementation
        $paymentCode = 'TRF-' . strtoupper(bin2hex(random_bytes(4)));
        
        return [
            'success' => true,
            'method' => 'Bank Transfer (' . $this->bankName . ')',
            'amount' => $amount,
            'payment_code' => $paymentCode,
            'instructions' => 'Please transfer IDR ' . number_format($amount, 2) . ' to ' . $this->bankName . ' account ' . $this->accountNumber,
            'status' => 'pending'
        ];
    }
}
