<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Food\Food;
use App\Models\Tenant\Food\FoodCategory;
use App\Traits\WithFilterTenantDateRange;
use App\Traits\WithMediaCollection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends BaseComponent
{
    use WithFileUploads, WithFilterTenantDateRange, WithMediaCollection;

    // Model instance
    public $modelInstance = Food::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Food Category',
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
            'name' => 'Description',
            'field' => 'food.description',
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

    public $foodCategories = [];

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'food.created_at';

        // Load tenants for tenant selection if super admin
        if (auth()->user()->isSuperAdmin()) {
            // Get all active tenants
            $this->fetchAllActiveTenants();
        }
    }

    public function getFoodCategories()
    {
        if ($this->tenant_id) {
            $this->foodCategories = FoodCategory::where('tenant_id', $this->tenant_id)
                ->where('status', CommonStatusEnum::ACTIVE)
                ->orderBy('name')
                ->get();
        }
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = Food::query()
            ->join('tenants', 'food.tenant_id', '=', 'tenants.id')
            ->join('food_categories', 'food.food_category_id', '=', 'food_categories.id')
            ->select(
                'food.*',
                'tenants.name as tenant_name',
                'food_categories.name as food_category_name',
            );

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('food.tenant_id', auth()->user()->tenant?->tenant_id);
        } else {
            $model = $this->applyFilterTenantDateRange(
                model: $model,
                tenantField: 'food.tenant_id',
                dateField: 'food.created_at',
            );
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

    public $oldData;

    public $tenant_id;

    public $food_category_id;

    public $name;

    public $price;

    public $description;

    public $status;

    public $image;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to view this record.');

            return;
        }

        $record = Food::find($id);
        $this->oldData = $record;
        $this->recordId = $record->id;
        $this->tenant_id = $record->tenant_id;
        $this->food_category_id = $record->food_category_id;
        $this->name = $record->name;
        $this->price = numberToCurrency($record->price);
        $this->description = $record->description;
        $this->status = $record->status->value;

        // Get contents for the tenant
        $this->getFoodCategories();

        // Set content_id value in the frontend
        $this->dispatch('setValueById', id: 'food_category_id', value: $this->food_category_id);
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'oldData',
            'tenant_id',
            'food_category_id',
            'name',
            'price',
            'description',
            'status',
        ]);

        $this->tenant_id = auth()->user()->tenant?->tenant_id;
        $this->price = 0;
        $this->status = CommonStatusEnum::ACTIVE->value;

        // Get contents for the tenant
        $this->getFoodCategories();
    }

    // Handle form submit
    public function submit()
    {
        $this->price = currencyToNumber($this->price);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'food_category_id' => 'required|exists:food_categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
            // Max 50MB
            'image' => 'nullable|image:allow_svg|max:51200',
        ]);

        $model = $this->save();

        // Handle image upload
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->image,
                collection: 'image',
            );
        }

        // + the version number on each action
        $model->version = Food::max('version') + 1;
        $model->save();

        $this->resetRecordData();
    }
};
