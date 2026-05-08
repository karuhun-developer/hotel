<?php

use App\Actions\Cms\Tenant\DeleteTenantAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    public $modelInstance = Tenant::class;

    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Type',
            'field' => 'tenants.type',
        ],
        [
            'name' => 'Branch',
            'field' => 'tenants.branch',
        ],
        [
            'name' => 'Email',
            'field' => 'tenants.email',
        ],
        [
            'name' => 'Status',
            'field' => 'tenants.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'tenants.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);
        $this->paginationOrderBy = 'tenants.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        $model = Tenant::query()
            ->when(! auth()->user()->isSuperAdmin(), function ($query) {
                $query->where('id', auth()->user()->tenant?->tenant_id);
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
    public function delete($id, DeleteTenantAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(Tenant::findOrFail($id));

        $this->dispatch('toast', type: 'success', message: 'Tenant deleted successfully.');
    }
};
