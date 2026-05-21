<?php

namespace App\Services\Adapters;

/**
 * Adapter Pattern: MidtransSDK (Adaptee)
 * Mimics a third-party SDK with a completely different method name and return type.
 */
class MidtransSDK
{
    private string $serverKey;

    public function __construct(string $serverKey = 'SB-Midtrans-Demo-Key')
    {
        $this->serverKey = $serverKey;
    }

    /**
     * Midtrans-specific API to create a payment transaction.
     * Note: This method name is completely different from our target interface.
     */
    public function requestSnapToken(array $params): array
    {
        // Simulate remote API call
        $snapToken = 'snap-token-' . bin2hex(random_bytes(16));
        $redirectUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken;
        
        return [
            'token' => $snapToken,
            'redirect_url' => $redirectUrl,
            'transaction_status' => 'settlement',
            'gross_amount' => $params['transaction_details']['gross_amount'] ?? 0,
            'order_id' => $params['transaction_details']['order_id'] ?? null
        ];
    }
}
