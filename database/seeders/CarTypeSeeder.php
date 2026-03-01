<?php

namespace Database\Seeders;

use App\Models\CarType;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    public function run(): void
    {
        $carTypes = [
            ['name' => 'Sedan', 'price_multiplier' => 1.00],
            ['name' => 'SUV', 'price_multiplier' => 1.30],
            ['name' => 'MPV', 'price_multiplier' => 1.35],
            ['name' => 'Coupe', 'price_multiplier' => 1.10],
            ['name' => 'Pickup', 'price_multiplier' => 1.40],
            ['name' => 'Luxury', 'price_multiplier' => 1.50],
        ];

        foreach ($carTypes as $carType) {
            CarType::create($carType);
        }
    }
}
