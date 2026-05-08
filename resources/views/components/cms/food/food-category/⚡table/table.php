<?php

use App\Actions\Cms\Food\FoodCategory\DeleteFoodCategoryAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Food\FoodCategory;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = FoodCategory::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Name',
            'field' => 'food_categories.name',
        ],
        [
            'name' => 'Description',
            'field' => 'food_categories.description',
        ],
        [
            'name' => 'Status',
            'field' => 'food_categories.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'food_categories.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'food_categories.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = FoodCategory::query()
            ->join('tenants', 'tenants.id', '=', 'food_categories.tenant_id')
            ->select('food_categories.*', 'tenants.name as tenant_name')
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('food_categories.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'food_categories.tenant_id', dateField: 'food_categories.created_at');

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
    public function delete($id, DeleteFoodCategoryAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            foodCategory: FoodCategory::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Food category deleted successfully.');
    }
};
