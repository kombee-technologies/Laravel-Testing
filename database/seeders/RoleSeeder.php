<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'Teacher', 'Student'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        echo "✅ Roles seeded successfully!";
    }
}
