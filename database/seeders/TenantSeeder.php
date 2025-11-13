<?php

namespace Database\Seeders;

use App\Enums\TenantTypeEnum;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant1 = Tenant::firstOrCreate(
            [
                'name' => 'Haris Hotel Bandung',
                'type' => TenantTypeEnum::HOTEL,
                'branch' => 'Bandung',
            ],
            [
                'address' => 'Jl. Merdeka No.1, Bandung',
                'phone' => '022-1234567',
                'email' => 'example@email.com',
                'website' => 'https://www.discoverasr.com/id/harris/indonesia/harris-hotel-conventions-ciumbuleuit-bandung',
                'default_greeting' => 'Welcome to Haris Hotel Bandung!',
                'password_setting' => '123456',
            ]
        );

        if (! $tenant1->profile) {
            $tenant1->profile()->create([
                'running_text' => 'Welcome to Haris Hotel Bandung',
                'primary_color' => '#FF5733',
                'description' => 'Experience comfort and convenience at Haris Hotel Bandung.',
                'welcome_text' => 'We are delighted to have you with us!',
            ]);
        }

        $tenant2 = Tenant::firstOrCreate(
            [
                'name' => 'Santosa Hospital Bandung Central',
                'type' => TenantTypeEnum::HOSPITAL,
                'branch' => 'Bandung',
            ],
            [
                'address' => 'Jl. Asia Afrika No.10, Bandung',
                'phone' => '022-7654321',
                'email' => 'example@email.com',
                'website' => 'https://www.santosa-hospital.com/',
                'default_greeting' => 'Welcome to Santosa Hospital Bandung Central!',
                'password_setting' => '123456',
            ]
        );

        if (! $tenant2->profile) {
            $tenant2->profile()->create([
                'running_text' => 'Welcome to Santosa Hospital Bandung Central',
                'primary_color' => '#33C3FF',
                'description' => 'Providing excellent healthcare services at Santosa Hospital Bandung Central.',
                'welcome_text' => 'Your health and well-being are our top priorities!',
            ]);
        }

        // Create admin hotel for each tenant
        foreach ([$tenant1, $tenant2] as $tenant) {
            $adminhotel = User::firstOrCreate(
                [
                    'email' => 'adminhotel@'.str()->slug($tenant->name).'.com',
                ],
                [
                    'name' => 'Admin Hotel '.$tenant->branch,
                    'password' => bcrypt('password'),
                ],
            );

            TenantUser::where('user_id', $adminhotel->id)->delete();
            TenantUser::create([
                'tenant_id' => $tenant->id,
                'user_id' => $adminhotel->id,
            ]);

            $adminhotel->syncRoles('hotel_admin');

            $receptionist = User::firstOrCreate(
                [
                    'email' => 'receptionist@'.str()->slug($tenant->name).'.com',
                ],
                [
                    'name' => 'Receptionist '.$tenant->branch,
                    'password' => bcrypt('password'),
                ],
            );

            TenantUser::where('user_id', $receptionist->id)->delete();
            TenantUser::create([
                'tenant_id' => $tenant->id,
                'user_id' => $receptionist->id,
            ]);

            $receptionist->syncRoles('hotel_receptionist');
        }
    }
}
