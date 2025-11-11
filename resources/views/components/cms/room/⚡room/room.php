<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Room;
use App\Models\Tenant\RoomType;
use App\Models\Tenant\Tenant;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = Room::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Room Type Name',
            'field' => 'room_types.name',
        ],
        [
            'name' => 'No',
            'field' => 'rooms.no',
        ],
        [
            'name' => 'Guest Name',
            'field' => 'rooms.guest_name',
        ],
        [
            'name' => 'Greeting',
            'field' => 'rooms.greeting',
        ],
        [
            'name' => 'Device Name',
            'field' => 'rooms.device_name',
        ],
        [
            'name' => 'Created At',
            'field' => 'rooms.created_at',
        ],
    ];

    public $tenants = [];

    public $roomTypes = [];

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'rooms.created_at';

        // Load tenants for tenant selection if super admin
        if (auth()->user()->isSuperAdmin()) {
            $this->tenants = Tenant::where('status', CommonStatusEnum::ACTIVE)->get();
        }
    }

    public function getRoomTypes()
    {
        if ($this->tenant_id) {
            $this->roomTypes = RoomType::query()
                ->where('tenant_id', $this->tenant_id)
                ->get();
        }
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = Room::query()
            ->join('tenants', 'tenants.id', '=', 'rooms.tenant_id')
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->select(
                'rooms.*',
                'room_types.name as room_type_name',
                'tenants.name as tenant_name',
            );

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('rooms.tenant_id', auth()->user()->tenant?->tenant_id);
        }

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

    public $tenant_id;

    public $room_type_id;

    public $no;

    public $guest_name;

    public $greeting;

    public $device_name;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to view this record.');

            return;
        }

        $record = RoomType::find($id);
        $this->recordId = $record->id;
        $this->tenant_id = $record->tenant_id;
        $this->room_type_id = $record->room_type_id;
        $this->no = $record->no;
        $this->guest_name = $record->guest_name;
        $this->greeting = $record->greeting;
        $this->device_name = $record->device_name;

        // Get room types for the tenant
        $this->getRoomTypes();

        // Set room type id
        $this->dispatch('setValueById', id: 'room_type_id', value: $this->room_type_id);
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'tenant_id',
            'room_type_id',
            'no',
            'guest_name',
            'greeting',
            'device_name',
        ]);

        $this->tenant_id = auth()->user()->tenant?->tenant_id;

        // Get room types for the tenant
        $this->getRoomTypes();
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_type_id' => 'required|exists:room_types,id',
            'no' => 'required|string|max:255',
            'guest_name' => 'nullable|string|max:255',
            'greeting' => 'nullable|string|max:255',
            'device_name' => 'nullable|string|max:255',
        ]);

        $this->save();
    }
};
