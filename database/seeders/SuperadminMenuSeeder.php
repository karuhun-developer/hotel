<?php

namespace Database\Seeders;

use App\Models\Menu\Menu;
use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;

class SuperadminMenuSeeder extends Seeder
{
    public $role;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->role = Role::where('name', 'superadmin')->first();
        Menu::where('role_id', $this->role->id)->delete();

        // Create menu
        $this->dashboardMenu();
        $this->frontDeskMenu();
        $this->tenantMenu();
        $this->applicationMenu();
        $this->roomMenu();
        $this->contentMenu();
        $this->foodMenu();
        $this->m3uMenu();
        $this->managementMenu();
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
            'url' => 'cms.application',
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
            'icon' => 'home',
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
            'name' => 'Content Items',
            'url' => 'cms.content.content-item',
            'order' => 2,
            'active_pattern' => 'cms.content.content-item',
            'status' => 1,
        ]);
    }

    public function foodMenu()
    {
        $food = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Food',
            'url' => '#',
            'icon' => 'gift',
            'order' => 7,
            'active_pattern' => 'cms.food',
            'status' => 1,
        ]);

        $food->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Categories',
            'url' => 'cms.food.food-categories',
            'order' => 1,
            'active_pattern' => 'cms.food.food-categories',
            'status' => 1,
        ]);
        $food->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Food Items',
            'url' => 'cms.food.food',
            'order' => 2,
            'active_pattern' => 'cms.food.food',
            'status' => 1,
        ]);
    }

    public function m3uMenu()
    {
        $m3u = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'M3U',
            'url' => '#',
            'icon' => 'tv',
            'order' => 8,
            'active_pattern' => 'cms.m3u',
            'status' => 1,
        ]);

        $m3u->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Sources',
            'url' => 'cms.m3u.index',
            'order' => 1,
            'active_pattern' => 'cms.m3u.index',
            'status' => 1,
        ]);
        $m3u->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Channels',
            'url' => 'cms.m3u.channel',
            'order' => 2,
            'active_pattern' => 'cms.m3u.channel',
            'status' => 1,
        ]);
    }

    public function managementMenu()
    {
        $management = Menu::create([
            'role_id' => $this->role->id,
            'name' => 'Managements',
            'url' => '#',
            'icon' => 'cog',
            'order' => 999,
            'active_pattern' => 'cms.management',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Permission',
            'url' => 'cms.management.permission',
            'order' => 1,
            'active_pattern' => 'cms.management.permission',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Role',
            'url' => 'cms.management.role',
            'order' => 2,
            'active_pattern' => 'cms.management.role',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'Menu',
            'url' => 'cms.management.menu',
            'order' => 3,
            'active_pattern' => 'cms.management.menu',
            'status' => 1,
        ]);
        $management->subMenu()->create([
            'role_id' => $this->role->id,
            'name' => 'User',
            'url' => 'cms.management.user',
            'order' => 4,
            'active_pattern' => 'cms.management.user',
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
