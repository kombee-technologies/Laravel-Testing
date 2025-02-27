<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [];

        for ($i = 0; $i < 30; $i++) {
            $customers[] = [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'contact_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'company_name' => fake()->company(),
                'job_title' => fake()->jobTitle(),
                'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
                'date_of_birth' => fake()->date(),
                'nationality' => fake()->country(),
                'customer_type' => fake()->randomElement(['Regular', 'VIP', 'Enterprise']),
                'notes' => fake()->sentence(),
                'preferred_contact_method' => fake()->randomElement(['Email', 'Phone', 'WhatsApp']),
                'newsletter_subscription' => fake()->boolean(),
                'account_balance' => fake()->randomFloat(2, 0, 10000),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('customers')->insert($customers);
    }
}
