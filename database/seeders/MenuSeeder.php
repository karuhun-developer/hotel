<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate();
        Menu::insert([
            [
                'name' => 'Dashboard',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'home',
                'route' => 'cms.dashboard',
                'ordering' => '1',
            ],
            // Settings
            [
                'name' => 'Settings',
                'on' => 'cms',
                'type' => 'header',
                'icon' => '#',
                'route' => '#',
                'ordering' => '30',
            ],
            [
                'name' => 'Menu',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'menu',
                'route' => 'cms.management.menu',
                'ordering' => '32',
            ],
            [
                'name' => 'Role',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'lock',
                'route' => 'cms.management.role',
                'ordering' => '33',
            ],
            [
                'name' => 'User',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'user',
                'route' => 'cms.management.user',
                'ordering' => '34',
            ],
            [
                'name' => 'Website',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'settings',
                'route' => 'cms.management.setting',
                'ordering' => '35',
            ],
            [
                'name' => 'Privacy Policies',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'file',
                'route' => 'cms.management.privacy-policy',
                'ordering' => '37',
            ],
            [
                'name' => 'Terms Of Service',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'file',
                'route' => 'cms.management.term-of-service',
                'ordering' => '38',
            ],
        ]);
    }
}
