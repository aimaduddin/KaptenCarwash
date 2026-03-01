<?php

namespace App\Services\Payment;

interface PaymentProviderInterface
{
    public function createCheckout(array $params): array;
    public function verifyCallback(array $payload): bool;
    public function mapStatus(string $providerStatus): string;
}
