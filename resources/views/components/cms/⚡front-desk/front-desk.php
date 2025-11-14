<?php

use App\Livewire\BaseComponent;
use App\Models\Tenant\FrontDesk;
use App\Models\Tenant\Room;
use App\Models\Tenant\RoomType;
use App\Models\Tenant\Tenant;
use App\Traits\WithFilterTenantDateRange;
use Flux\Flux;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

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
            'name' => 'Is Birthday',
            'field' => 'rooms.is_birthday',
        ],
    ];

    public $roomTypes = [];

    public $filterRoomType = '';

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
            // Get all active tenants
            $this->fetchAllActiveTenants();
        } else {
            // Set tenant id for non-super admin users
            $this->tenant_id = auth()->user()->tenant?->tenant_id;
            $this->getRoomTypes();
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
        } else {
            $model = $this->applyFilterTenantDateRange(
                model: $model,
                tenantField: 'rooms.tenant_id',
                dateField: 'rooms.created_at',
            );
        }

        $model->when($this->filterRoomType != '', function ($q) {
            $q->where('rooms.room_type_id', $this->filterRoomType);
        });

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

    public $roomData;

    public $check_in;

    public $check_out;

    public $guest_name;

    public $is_birthday;

    public function checkIn($id)
    {
        $record = Room::find($id);

        $this->roomData = $record;
        $this->recordId = $record->id;
        $this->check_in = now();
        $this->check_out = null;
        $this->guest_name = '';
        $this->is_birthday = false;

        Flux::modal('defaultModal')->show();
    }

    #[On('checkOut')]
    public function checkOut($id)
    {
        $record = Room::find($id);
        $record->guest_name = null;
        $record->is_birthday = false;

        $record->save();

        // Update Front Desk Activity
        $frontDesk = FrontDesk::where('room_id', $record->id)
            ->whereNull('check_out')
            ->latest()
            ->first();

        if ($frontDesk) {
            $frontDesk->check_out = now();
            $frontDesk->save();
        }

        // Toast notification
        $this->dispatch('toast', type: 'success', message: 'Checked out success.');
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'check_in' => 'required|date',
            'guest_name' => 'required|string|max:255',
            'is_birthday' => 'boolean',
        ]);

        // Update record
        $record = Room::find($this->recordId);
        $record->guest_name = $this->guest_name;
        $record->is_birthday = $this->is_birthday;
        $record->save();

        // Log Front Desk Activity
        FrontDesk::create([
            'tenant_id' => $record->tenant_id,
            'room_id' => $record->id,
            'guest_name' => $this->guest_name,
            'check_in' => $this->check_in,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Checked in success.');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
