<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\VehicleTypeSeeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed locations and vehicle types first
        $this->call([
            LocationSeeder::class,
            VehicleTypeSeeder::class,
        ]);

        
    }
}
