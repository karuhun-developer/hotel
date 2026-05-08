<?php

use App\Actions\Cms\FrontDesk\DeleteFrontDeskAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\FrontDesk;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = FrontDesk::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Room No',
            'field' => 'rooms.no',
        ],
        [
            'name' => 'Guest Name',
            'field' => 'front_desks.guest_name',
        ],
        [
            'name' => 'Check In',
            'field' => 'front_desks.check_in',
        ],
        [
            'name' => 'Check Out',
            'field' => 'front_desks.check_out',
        ],
        [
            'name' => 'Created At',
            'field' => 'front_desks.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'front_desks.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = FrontDesk::query()
            ->join('tenants', 'tenants.id', '=', 'front_desks.tenant_id')
            ->join('rooms', 'rooms.id', '=', 'front_desks.room_id')
            ->select('front_desks.*', 'tenants.name as tenant_name', 'rooms.no as room_no');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('front_desks.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'front_desks.tenant_id', dateField: 'front_desks.created_at');

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
    public function delete($id, DeleteFrontDeskAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            frontDesk: FrontDesk::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Front Desk deleted successfully.');
    }
};
