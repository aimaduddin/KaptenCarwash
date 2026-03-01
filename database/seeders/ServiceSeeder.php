<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Exterior Wash', 'base_price' => 2000, 'duration_minutes' => 30],
            ['name' => 'Interior Vacuum', 'base_price' => 1500, 'duration_minutes' => 20],
            ['name' => 'Wax & Polish', 'base_price' => 5000, 'duration_minutes' => 45],
            ['name' => 'Engine Bay Cleaning', 'base_price' => 3000, 'duration_minutes' => 30],
            ['name' => 'Leather Conditioning', 'base_price' => 4000, 'duration_minutes' => 35],
            ['name' => 'Window Tinting', 'base_price' => 8000, 'duration_minutes' => 60],
            ['name' => 'Clay Bar Treatment', 'base_price' => 3500, 'duration_minutes' => 40],
            ['name' => 'Headlight Restoration', 'base_price' => 2500, 'duration_minutes' => 30],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
