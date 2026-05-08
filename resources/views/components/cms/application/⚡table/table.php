<?php

use App\Actions\Cms\Application\DeleteApplicationAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Application;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = Application::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Name',
            'field' => 'applications.name',
        ],
        [
            'name' => 'Package Name',
            'field' => 'applications.package_name',
        ],
        [
            'name' => 'Created At',
            'field' => 'applications.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'applications.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = Application::query()
            ->join('tenants', 'tenants.id', '=', 'applications.tenant_id')
            ->select('applications.*', 'tenants.name as tenant_name')
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('applications.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'applications.tenant_id', dateField: 'applications.created_at');

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
    public function delete($id, DeleteApplicationAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            application: Application::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Application deleted successfully.');
    }
};
