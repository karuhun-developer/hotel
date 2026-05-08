<?php

use App\Actions\Cms\Room\Room\DeleteRoomAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Room;
use App\Models\Tenant\RoomType;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = Room::class;

    #[Url(as: 'room_type', except: '')]
    public $roomTypeFilter = '';

    #[Computed]
    public function roomTypes()
    {
        $query = RoomType::query();
        if (! auth()->user()->isSuperAdmin()) {
            $query->where('tenant_id', auth()->user()->tenant?->tenant_id);
        } elseif ($this->tenantFilter) {
            $query->where('tenant_id', $this->tenantFilter);
        }

        return $query->get();
    }

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Room Type',
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

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'rooms.created_at';
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
            ->select('rooms.*', 'tenants.name as tenant_name', 'room_types.name as room_type_name');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('rooms.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'rooms.tenant_id', dateField: 'rooms.created_at');

        $model->when($this->roomTypeFilter, function ($query) {
            $query->where('rooms.room_type_id', $this->roomTypeFilter);
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

    #[On('delete')]
    public function delete($id, DeleteRoomAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            room: Room::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Room deleted successfully.');
    }
};
