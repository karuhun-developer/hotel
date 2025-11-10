<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\Menu\Menu;
use App\Models\Spatie\Role;
use Illuminate\Support\Facades\File;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = Menu::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Role',
            'field' => 'roles.name',
        ],
        [
            'name' => 'Menu',
            'field' => 'menus.name',
        ],
        [
            'name' => 'URL',
            'field' => 'menus.url',
        ],
        [
            'name' => 'Icon',
            'field' => 'menus.icon',
        ],
        [
            'name' => 'Order',
            'field' => 'menus.order',
        ],
        [
            'name' => 'Active Pattern',
            'field' => 'menus.active_pattern',
        ],
        [
            'name' => 'Status',
            'field' => 'menus.status',
        ],
    ];

    // Roles list
    public $roles;

    // Icons list
    public $icons;

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'menus.order';

        // Get list of roles
        $this->roles = Role::all();
        // Get list of icons
        $this->icons = collect(File::allFiles(resource_path('views/flux/icon')))->map(function ($file) {
            return str_replace('.blade.php', '', $file->getFilename());
        })->values()->toArray();
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = Menu::query()
            ->join('roles', 'menus.role_id', '=', 'roles.id')
            ->select(
                'menus.*',
                'roles.name as role_name',
            );

        $data = $this->getDataWithFilter(
            model: $model,
            searchBy: $this->searchBy,
            orderBy: $this->paginationOrderBy,
            order: $this->paginationOrder,
            paginate: $this->paginate,
            s: $this->search,
        );

        return $this->view([
            'data' => $data,
        ]);
    }

    // Record data
    public $recordId;

    public $role_id;

    public $name;

    public $url;

    public $icon;

    public $order;

    public $active_pattern;

    public $status;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        $record = Menu::find($id);
        $this->recordId = $record->id;
        $this->role_id = $record->role_id;
        $this->name = $record->name;
        $this->url = $record->url;
        $this->icon = $record->icon;
        $this->order = $record->order;
        $this->active_pattern = $record->active_pattern;
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'role_id',
            'name',
            'url',
            'icon',
            'order',
            'active_pattern',
            'status',
        ]);

        $this->icon = 'house';
        $this->status = 1;
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'active_pattern' => 'nullable|string|max:255',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
        ]);

        $this->save();
    }
};
