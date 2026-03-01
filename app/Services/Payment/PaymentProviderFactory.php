<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Config;

class PaymentProviderFactory
{
    public static function create(): PaymentProviderInterface
    {
        $provider = Config::get('kapten.payment_provider', 'mock');

        return match ($provider) {
            'mock' => new MockPaymentProvider(),
            default => throw new \InvalidArgumentException("Unknown payment provider: {$provider}"),
        };
    }
}
