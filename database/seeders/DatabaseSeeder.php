<?php

namespace Database\Seeders;

use App\Models\CarType;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CarTypeSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
