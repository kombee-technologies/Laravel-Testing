<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run()
    {
        $cities = [
            ['name' => 'Ahmedabad', 'state_id' => 1],
            ['name' => 'Surat', 'state_id' => 1],
            ['name' => 'Vadodara', 'state_id' => 1],
            ['name' => 'Mumbai', 'state_id' => 2],
            ['name' => 'Pune', 'state_id' => 2],
            ['name' => 'Jaipur', 'state_id' => 3],
            ['name' => 'Jodhpur', 'state_id' => 3],
            ['name' => 'Lucknow', 'state_id' => 4],
            ['name' => 'Kanpur', 'state_id' => 4],
            ['name' => 'Bengaluru', 'state_id' => 5],
            ['name' => 'Mysore', 'state_id' => 5]
        ];

        City::insert($cities);
    }
}

