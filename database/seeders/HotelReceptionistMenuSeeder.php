<?php

namespace Database\Seeders;

use App\Models\Menu\Menu;
use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;

class HotelReceptionistMenuSeeder extends Seeder
{
    public $role;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->role = Role::where('name', 'hotel_receptionist')->first();
        Menu::where('role_id', $this->role->id)->delete();

        $this->dashboardMenu();
        $this->frontDeskMenu();
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
}
