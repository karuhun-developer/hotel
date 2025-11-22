<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Food\FoodCategory;
use App\Traits\WithFilterTenantDateRange;
use App\Traits\WithMediaCollection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends BaseComponent
{
    use WithFileUploads, WithFilterTenantDateRange, WithMediaCollection;

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
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'food_categories.created_at';

        // Load tenants for tenant selection if super admin
        if (auth()->user()->isSuperAdmin()) {
            // Get all active tenants
            $this->fetchAllActiveTenants();
        }
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = FoodCategory::query()
            ->join('tenants', 'tenants.id', '=', 'food_categories.tenant_id')
            ->select(
                'food_categories.*',
                'tenants.name as tenant_name',
            )
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('food_categories.tenant_id', auth()->user()->tenant?->tenant_id);
        } else {
            $model = $this->applyFilterTenantDateRange(
                model: $model,
                tenantField: 'food_categories.tenant_id',
                dateField: 'food_categories.created_at',
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

    public $name;

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

        $record = FoodCategory::find($id);
        $this->oldData = $record;
        $this->recordId = $record->id;
        $this->tenant_id = $record->tenant_id;
        $this->name = $record->name;
        $this->description = $record->description;
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'oldData',
            'tenant_id',
            'name',
            'description',
            'status',
            'image',
        ]);

        $this->tenant_id = auth()->user()->tenant?->tenant_id;
        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
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
        $model->version = FoodCategory::max('version') + 1;
        $model->save();

        $this->resetRecordData();
    }
};
