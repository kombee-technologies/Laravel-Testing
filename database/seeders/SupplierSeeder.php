<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 30) as $index) {
            DB::table('suppliers')->insert([
                'name' => $faker->company,
                'email' => $faker->unique()->safeEmail,
                'contact_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'company_name' => $faker->company,
                'gst_number' => $faker->regexify('[0-9]{15}'),
                'website' => $faker->url,
                'country' => $faker->country,
                'state' => $faker->state,
                'city' => $faker->city,
                'postal_code' => $faker->postcode,
                'contact_person' => $faker->name,
                'status' => $faker->randomElement(['active', 'inactive']),
                'contract_start_date' => $faker->date(),
                'contract_end_date' => $faker->date(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
