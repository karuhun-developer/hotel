<?php

use App\Actions\Cms\Room\RoomType\DeleteRoomTypeAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\RoomType;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = RoomType::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Name',
            'field' => 'room_types.name',
        ],
        [
            'name' => 'Description',
            'field' => 'room_types.description',
        ],
        [
            'name' => 'Created At',
            'field' => 'room_types.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'room_types.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = RoomType::query()
            ->join('tenants', 'tenants.id', '=', 'room_types.tenant_id')
            ->select('room_types.*', 'tenants.name as tenant_name')
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('room_types.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'room_types.tenant_id', dateField: 'room_types.created_at');

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
    public function delete($id, DeleteRoomTypeAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            roomType: RoomType::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Room type deleted successfully.');
    }
};
