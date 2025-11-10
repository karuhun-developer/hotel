<?php

use App\Livewire\BaseComponent;
use App\Models\Spatie\Permission;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = Permission::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'name',
        ],
        [
            'name' => 'Guard Name',
            'field' => 'guard_name',
        ],
    ];

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'guard_name';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $data = $this->getDataWithFilter(
            model: new Permission,
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

    public $permission_id;

    public $name;

    public $guard_name;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        $record = Permission::find($id);
        $this->recordId = $record->id;
        $this->name = $record->name;
        $this->guard_name = $record->guard_name;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'name',
            'guard_name',
        ]);

        $this->guard_name = 'api';
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        $this->save();
    }
};
