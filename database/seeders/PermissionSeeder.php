<?php

namespace Database\Seeders;

use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;
use App\Models\Tenant\Tenant;
use App\Models\User;
use App\Models\User\ApiKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    // Define the prefix for permissions
    // This will be used to create permissions for each model
    private $prefixPermission = [
        'view',
        'show',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
        'export',
        'import',
        'validate',
    ];

    // Guard name for the permissions
    private $guardName = 'api';

    // Superadmin can't do
    private $superAdminExcludePermission = [
    ];

    // List hotel admin permissions
    private $hotelAdminPermissions = [
        // User management on his own hotel
        'view'.User::class,
        'show'.User::class,
        'create'.User::class,
        'update'.User::class,
        'delete'.User::class,
        // API Key permissions
        'view'.ApiKey::class,
        'show'.ApiKey::class,
        'create'.ApiKey::class,
        'update'.ApiKey::class,
        'delete'.ApiKey::class,
        // Tenant management on his own hotel
        'view'.Tenant::class,
        'show'.Tenant::class,
        'update'.Tenant::class,
    ];

    // List hotel receptionist permissions
    private $hotelReceptionistPermissions = [

    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Read all models exists
        $models = $this->getModelLists();

        // Create roles
        $roleSuperAdmin = Role::findOrCreate('superadmin', $this->guardName);
        $roleHotelAdmin = Role::findOrCreate('hotel_admin', $this->guardName);
        $roleHotelReceptionist = Role::findOrCreate('hotel_receptionist', $this->guardName);

        // Loop through each model and create permissions
        foreach ($this->prefixPermission as $permission) {
            foreach ($models as $model) {
                $permissionName = $permission.$model;
                Permission::query()
                    ->where('name', $permissionName)
                    ->where('guard_name', $this->guardName)
                    ->firstOrCreate([
                        'name' => $permissionName,
                        'guard_name' => $this->guardName,
                    ]);

                // Assign permissions to roles
                if (in_array($permissionName, $this->hotelAdminPermissions)) {
                    $roleHotelAdmin->givePermissionTo($permissionName);
                }

                if (in_array($permissionName, $this->hotelReceptionistPermissions)) {
                    $roleHotelReceptionist->givePermissionTo($permissionName);
                }

                // Exclude superadmin permissions
                if (! in_array($permissionName, $this->superAdminExcludePermission)) {
                    $roleSuperAdmin->givePermissionTo($permissionName);
                }
            }
        }
    }

    /**
     * Get the list of models from the app directory.
     */
    private function getModelLists(): array
    {
        return collect(File::allFiles(app_path('Models')))
            ->filter(function ($file) {
                return $file->getExtension() === 'php';
            })
            ->map(function ($file) {
                $className = 'App\\Models\\'.str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());

                return class_exists($className) ? $className : null;
            })
            ->filter()
            ->toArray();
    }
}
