<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            // Ensure states exist
            $state = State::inRandomOrder()->first() ?? State::factory()->create();

            // Ensure cities exist for the chosen state
            $city = City::where('state_id', $state->id)->inRandomOrder()->first() ?? City::factory()->create(['state_id' => $state->id]);

            User::create([
                'first_name'     => $faker->firstName,
                'last_name'      => $faker->lastName,
                'email'          => $faker->unique()->safeEmail,
                'contact_number' => $faker->phoneNumber,
                'postcode'       => $faker->postcode,
                'password'       => Hash::make('password'), // Default password for all users
                'gender'         => $faker->randomElement(['male', 'female']),
                'state_id'       => $state->id,
                'city_id'        => $city->id,
                'hobbies'        => json_encode($faker->randomElements(['reading', 'traveling', 'sports', 'music', 'coding'], rand(2, 4))),
                'uploaded_files' => json_encode(['uploads/' . $faker->word . '.pdf'])
            ]);
        }
    }
}
