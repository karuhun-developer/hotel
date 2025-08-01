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
        Menu::insert([
            [
                'name' => 'Dashboard',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'home',
                'route' => 'cms.dashboard',
                'ordering' => '1',
            ],
            [
                'name' => 'Front Desk',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'book-open',
                'route' => 'cms.front-desk',
                'ordering' => '2',
            ],
            [
                'name' => 'Documentation',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'file-text',
                'route' => 'cms.docs',
                'ordering' => '3',
            ],
            // Master Data
            [
                'name' => 'Master',
                'on' => 'cms',
                'type' => 'header',
                'icon' => '#',
                'route' => '#',
                'ordering' => '10',
            ],
            [
                'name' => 'Feature',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'briefcase',
                'route' => 'cms.master.feature',
                'ordering' => '11',
            ],
            [
                'name' => 'Hotel',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'briefcase',
                'route' => 'cms.master.hotel',
                'ordering' => '12',
            ],
            [
                'name' => 'Hospital',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'heart',
                'route' => 'cms.master.hospital',
                'ordering' => '13',
            ],
            [
                'name' => 'Room Type',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'tag',
                'route' => 'cms.master.room-type',
                'ordering' => '14',
            ],
            [
                'name' => 'Room',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'save',
                'route' => 'cms.master.room',
                'ordering' => '15',
            ],
            // Hospital
            [
                'name' => 'Hospital',
                'on' => 'cms',
                'type' => 'header',
                'icon' => '#',
                'route' => '#',
                'ordering' => '17',
            ],
            [
                'name' => 'Doctor Category',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'book-open',
                'route' => 'cms.hospital.doctor-category',
                'ordering' => '18',
            ],
            [
                'name' => 'Doctor',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'users',
                'route' => 'cms.hospital.doctor',
                'ordering' => '19',
            ],
            // Hotel
            [
                'name' => 'Hotel',
                'on' => 'cms',
                'type' => 'header',
                'icon' => '#',
                'route' => '#',
                'ordering' => '20',
            ],
            [
                'name' => 'Policy',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'book',
                'route' => 'cms.hotel.policy',
                'ordering' => '21',
            ],
            [
                'name' => 'Facility',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'archive',
                'route' => 'cms.hotel.facility',
                'ordering' => '22',
            ],
            [
                'name' => 'Around',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'globe',
                'route' => 'cms.hotel.around',
                'ordering' => '23',
            ],
            [
                'name' => 'Promo',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'percent',
                'route' => 'cms.hotel.promo',
                'ordering' => '24',
            ],
            [
                'name' => 'Food Category',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'book-open',
                'route' => 'cms.hotel.food-category',
                'ordering' => '25',
            ],
            [
                'name' => 'Food',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'book-open',
                'route' => 'cms.hotel.food',
                'ordering' => '26',
            ],
            [
                'name' => 'Event',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'calendar',
                'route' => 'cms.hotel.event',
                'ordering' => '27',
            ],
            [
                'name' => 'Wifi',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'wifi',
                'route' => 'cms.hotel.wifi',
                'ordering' => '28',
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
                'ordering' => '31',
            ],
            [
                'name' => 'M3U Source',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'list',
                'route' => 'cms.management.m3u-channel',
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
                'name' => 'Access Control',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'key',
                'route' => 'cms.management.access-control',
                'ordering' => '36',
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
