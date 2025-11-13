<?php

namespace Database\Seeders;

use App\Models\Menu\Menu;
use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;

class HotelAdminMenuSeeder extends Seeder
{
    public $role;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->role = Role::where('name', 'hotel_admin')->first();
        Menu::where('role_id', $this->role->id)->delete();

        $this->dashboardMenu();
        $this->frontDeskMenu();
        $this->tenantMenu();
        $this->applicationMenu();
        $this->roomMenu();
        $this->contentMenu();
        $this->apiKeyMenu();
    }

    public function dashboardMenu()
    {
        Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Dashboard',
            'url' => 'cms.dashboard',
            'icon' => 'map',
            'order' => 1,
            'active_pattern' => 'cms.dashboard',
            'status' => 1,
        ]);
    }

    public function frontDeskMenu()
    {
        Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Front Desk',
            'url' => 'cms.front-desk',
            'icon' => 'computer-desktop',
            'order' => 2,
            'active_pattern' => 'cms.front-desk',
            'status' => 1,
        ]);
    }

    public function tenantMenu()
    {
        Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Tenants',
            'url' => 'cms.tenant.index',
            'icon' => 'building-office',
            'order' => 3,
            'active_pattern' => 'cms.tenant',
            'status' => 1,
        ]);
    }

    public function applicationMenu()
    {
        Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Applications',
            'url' => 'cms.application.index',
            'icon' => 'device-phone-mobile',
            'order' => 4,
            'active_pattern' => 'cms.application',
            'status' => 1,
        ]);
    }

    public function roomMenu()
    {
        $room = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Rooms',
            'url' => '#',
            'icon' => 'door-open',
            'order' => 5,
            'active_pattern' => 'cms.room',
            'status' => 1,
        ]);

        $room->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Room Types',
            'url' => 'cms.room.room-type',
            'order' => 1,
            'active_pattern' => 'cms.room.room-type',
            'status' => 1,
        ]);
        $room->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Rooms',
            'url' => 'cms.room.room',
            'order' => 2,
            'active_pattern' => 'cms.room.room',
            'status' => 1,
        ]);
    }

    public function contentMenu()
    {
        $content = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Contents',
            'url' => '#',
            'icon' => 'document-text',
            'order' => 6,
            'active_pattern' => 'cms.content',
            'status' => 1,
        ]);

        $content->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Contents',
            'url' => 'cms.content.content',
            'order' => 1,
            'active_pattern' => 'cms.content.content',
            'status' => 1,
        ]);
        $content->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Items',
            'url' => 'cms.content.item',
            'order' => 2,
            'active_pattern' => 'cms.content.item',
            'status' => 1,
        ]);
    }

    public function apiKeyMenu()
    {
        Menu::create([
            'role_id' => $this->role->id,
            'name' => 'API Key',
            'url' => 'cms.api-key',
            'icon' => 'key',
            'order' => 1000,
            'active_pattern' => 'cms.api-key',
            'status' => 1,
        ]);
    }
}
