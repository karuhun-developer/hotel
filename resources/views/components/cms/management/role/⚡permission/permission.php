<?php

use App\Livewire\BaseComponent;
use App\Models\Spatie\Permission;
use App\Models\Spatie\Role;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = Permission::class;

    // List permissions
    public $permissions = [];

    // Role instance
    public Role $role;

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('validate'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Get role permissions
        $this->getPermissions();
    }

    protected function getPermissions()
    {
        $permission = Permission::all();

        // Get all permission that avaliable
        foreach ($permission as $perm) {
            $perm = explode('App\\', $perm->name);
            $model = 'App\\'.$perm[1];
            $permssion = $perm[0];

            $this->permissions[$model][$permssion] = false;
        }

        // Check if role has permissions
        foreach ($this->role->permissions->pluck('name') as $permission) {
            $perm = explode('App\\', $permission);
            $model = 'App\\'.$perm[1];
            $permssion = $perm[0];

            $this->permissions[$model][$permssion] = true;
        }
    }

    // Check all
    public function checkAll()
    {
        foreach ($this->permissions as $key => $value) {
            foreach ($value as $k => $v) {
                $this->check($k, $key);
                $this->permissions[$key][$k] = true;
            }
        }

        // Alert success message
        $this->dispatch('toast', type: 'success', message: 'All permissions have been granted.');
    }

    // Uncheck all
    public function uncheckAll()
    {
        foreach ($this->permissions as $key => $value) {
            foreach ($value as $k => $v) {
                $this->uncheck($k, $key);
                $this->permissions[$key][$k] = false;
            }
        }

        // Alert success message
        $this->dispatch('toast', type: 'success', message: 'All permissions have been revoked.');
    }

    // Check
    public function check($action, $model)
    {
        $permission = $action.$model;
        $this->isPermissionExist($permission);
        $this->role->givePermissionTo($permission);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->role)
            ->withProperties([
                'permission' => $permission,
            ])
            ->event('check-permission')
            ->log('Add permission');

        // Alert success message
        $this->dispatch('toast', type: 'success', message: 'Permission has been granted.');
    }

    // Uncheck
    public function uncheck($action, $model)
    {
        $permission = $action.$model;
        $this->isPermissionExist($permission);
        $this->role->revokePermissionTo($permission);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->role)
            ->withProperties([
                'permission' => $permission,
            ])
            ->event('uncheck-permission')
            ->log('Remove permission');

        // Alert success message
        $this->dispatch('toast', type: 'success', message: 'Permission has been revoked.');
    }

    // Is Permission Exist
    public function isPermissionExist($permission)
    {
        $isPermissionExist = Permission::where('name', $permission)->first();
        if (is_null($isPermissionExist)) {
            return false;
        }
    }
};
