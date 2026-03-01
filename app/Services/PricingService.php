<?php

namespace App\Services;

use App\Models\CarType;
use App\Models\Service;
use Illuminate\Support\Collection;

class PricingService
{
    public static function calculateEffectivePrice(float $priceMultiplier, int $basePrice): int
    {
        return (int) round($basePrice * $priceMultiplier);
    }

    public static function calculateBookingPrice(CarType $carType, Collection $services, int $bookingFeePercent = 10): array
    {
        $totalServicePrice = $services->sum(fn (Service $service) => self::calculateEffectivePrice(
            $carType->price_multiplier,
            $service->base_price
        ));

        $bookingFee = (int) round($totalServicePrice * ($bookingFeePercent / 100));
        $totalPrice = $totalServicePrice + $bookingFee;

        return [
            'totalServicePrice' => $totalServicePrice,
            'bookingFee' => $bookingFee,
            'totalPrice' => $totalPrice,
        ];
    }
}
