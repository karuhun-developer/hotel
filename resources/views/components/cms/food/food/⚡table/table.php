<?php

use App\Actions\Cms\Food\Food\DeleteFoodAction;
use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Food\Food;
use App\Models\Tenant\Food\FoodCategory;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = Food::class;

    #[Url(as: 'category', except: '')]
    public $foodCategoryFilter = '';

    #[Computed]
    public function foodCategories()
    {
        $query = FoodCategory::where('status', CommonStatusEnum::ACTIVE);
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
            'name' => 'Category',
            'field' => 'food_categories.name',
        ],
        [
            'name' => 'Name',
            'field' => 'food.name',
        ],
        [
            'name' => 'Price',
            'field' => 'food.price',
        ],
        [
            'name' => 'Status',
            'field' => 'food.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'food.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'food.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = Food::query()
            ->join('tenants', 'tenants.id', '=', 'food.tenant_id')
            ->join('food_categories', 'food_categories.id', '=', 'food.food_category_id')
            ->select('food.*', 'tenants.name as tenant_name', 'food_categories.name as category_name')
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('food.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'food.tenant_id', dateField: 'food.created_at');

        $model->when($this->foodCategoryFilter, function ($query) {
            $query->where('food.food_category_id', $this->foodCategoryFilter);
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
    public function delete($id, DeleteFoodAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            food: Food::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Food deleted successfully.');
    }
};
