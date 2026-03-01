<?php

namespace App\Services\Payment;

class MockPaymentProvider implements PaymentProviderInterface
{
    public function createCheckout(array $params): array
    {
        return [
            'checkoutUrl' => route('payment.dummy', $params['bookingId']),
            'reference' => "MOCK-{$params['bookingId']}",
        ];
    }

    public function verifyCallback(array $payload): bool
    {
        return true;
    }

    public function mapStatus(string $providerStatus): string
    {
        return match (strtolower($providerStatus)) {
            'success' => 'PAID',
            'failed' => 'FAILED',
            'cancelled' => 'FAILED',
            default => 'UNPAID',
        };
    }
}
