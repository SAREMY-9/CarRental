<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        Location::insert([
            ['name' => 'Nairobi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mombasa', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kisumu', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
