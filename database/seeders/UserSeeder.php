<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::firstOrCreate(
            [
                'email' => 'superadmin@superadmin.com',
            ],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ],
        );

        // Add role to the superadmin user
        $superadmin->syncRoles(['superadmin']);
        $superadmin->markEmailAsVerified();
    }
}
