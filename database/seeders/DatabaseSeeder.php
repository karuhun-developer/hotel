<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            TruncateTable::class,
            RoleSeeder::class,
             HotelSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            SettingSeeder::class,
            FeatureSeeder::class,
            // RoomSeeder::class,
            // M3USeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
