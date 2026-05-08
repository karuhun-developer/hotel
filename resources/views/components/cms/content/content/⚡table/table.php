<?php

use App\Actions\Cms\Content\Content\DeleteContentAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Content\Content;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = Content::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Name',
            'field' => 'contents.name',
        ],
        [
            'name' => 'Status',
            'field' => 'contents.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'contents.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'contents.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = Content::query()
            ->join('tenants', 'tenants.id', '=', 'contents.tenant_id')
            ->select('contents.*', 'tenants.name as tenant_name')
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('contents.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'contents.tenant_id', dateField: 'contents.created_at');

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
    public function delete($id, DeleteContentAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            content: Content::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Content deleted successfully.');
    }
};
