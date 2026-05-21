<?php

namespace App\Services\Adapters;

/**
 * Adapter Pattern: MidtransAdapter (Adapter)
 * Adapts the MidtransSDK to fit our standard PaymentGatewayAdapter interface.
 */
class MidtransAdapter implements PaymentGatewayAdapter
{
    private MidtransSDK $midtransSdk;

    public function __construct(MidtransSDK $sdk)
    {
        $this->midtransSdk = $sdk;
    }

    public function processPayment(int $orderId, float $amount): array
    {
        // Re-format request for Midtrans SDK requirements
        $params = [
            'transaction_details' => [
                'order_id' => 'CAMPUS-EVENT-' . $orderId,
                'gross_amount' => $amount
            ]
        ];

        // Call the Adaptee
        $response = $this->midtransSdk->requestSnapToken($params);

        // Map response back to standard format
        return [
            'gateway' => 'Midtrans',
            'success' => true,
            'transaction_id' => $response['token'],
            'redirect_url' => $response['redirect_url'],
            'amount_charged' => $response['gross_amount'],
            'status' => 'success'
        ];
    }
}
