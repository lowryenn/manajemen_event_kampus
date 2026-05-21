<?php

namespace App\Services\Payments;

/**
 * Strategy Pattern: EWalletStrategy
 * Concrete strategy for processing payments via E-Wallets (GOPAY, OVO, ShopeePay, etc.).
 */
class EWalletStrategy implements PaymentStrategy
{
    private string $provider;

    public function __construct(string $provider = 'GOPAY')
    {
        $this->provider = $provider;
    }

    public function pay(float $amount): array
    {
        // Simple dummy e-wallet payment code or QR generation
        $paymentCode = 'QR-' . strtoupper($this->provider) . '-' . strtoupper(bin2hex(random_bytes(4)));
        
        return [
            'success' => true,
            'method' => 'E-Wallet (' . $this->provider . ')',
            'amount' => $amount,
            'payment_code' => $paymentCode,
            'instructions' => 'Scan QR code in your ' . $this->provider . ' app to pay IDR ' . number_format($amount, 2),
            'status' => 'pending'
        ];
    }
}
