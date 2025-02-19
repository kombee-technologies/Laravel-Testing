<?php

namespace Database\Seeders;
use App\Models\State;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $states = [
            ['name' => 'Gujarat'],
            ['name' => 'Maharashtra'],
            ['name' => 'Rajasthan'],
            ['name' => 'Uttar Pradesh'],
            ['name' => 'Karnataka']
        ];

        State::insert($states);
}
}
