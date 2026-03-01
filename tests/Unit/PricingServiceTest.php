<?php

namespace Tests\Unit;

use App\Models\CarType;
use App\Models\Service;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CarType::create(['name' => 'Sedan', 'price_multiplier' => 1.0]);
        CarType::create(['name' => 'SUV', 'price_multiplier' => 1.3]);

        Service::create(['name' => 'Exterior Wash', 'base_price' => 2000, 'duration_minutes' => 30]);
        Service::create(['name' => 'Wax & Polish', 'base_price' => 5000, 'duration_minutes' => 45]);
    }

    public function test_calculates_effective_price_correctly(): void
    {
        $carType = CarType::where('name', 'Sedan')->first();
        $service = Service::where('name', 'Exterior Wash')->first();

        $result = PricingService::calculateEffectivePrice($carType->price_multiplier, $service->base_price);

        $this->assertEquals(2000, $result); // 2000 * 1.0 = 2000
    }

    public function test_calculates_price_with_multiplier(): void
    {
        $carType = CarType::where('name', 'SUV')->first();
        $service = Service::where('name', 'Exterior Wash')->first();

        $result = PricingService::calculateEffectivePrice($carType->price_multiplier, $service->base_price);

        $this->assertEquals(2600, $result); // 2000 * 1.3 = 2600
    }

    public function test_calculates_total_booking_price_with_booking_fee(): void
    {
        $carType = CarType::where('name', 'Sedan')->first();
        $services = Service::whereIn('name', ['Exterior Wash', 'Wax & Polish'])->get();

        $result = PricingService::calculateBookingPrice($carType, $services, 10);

        $this->assertEquals(7000, $result['totalServicePrice']); // 2000 + 5000
        $this->assertEquals(700, $result['bookingFee']); // 7000 * 0.10 = 700
        $this->assertEquals(7700, $result['totalPrice']); // 7000 + 700
    }
}
