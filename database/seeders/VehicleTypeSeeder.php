<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    public function run()
    {
        VehicleType::insert([
            ['name' => 'Sedan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SUV', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Truck', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hatchback', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
