<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Content\Content;
use App\Models\Tenant\Content\ContentItem;
use App\Traits\WithFilterTenantDateRange;
use App\Traits\WithMediaCollection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends BaseComponent
{
    use WithFileUploads, WithFilterTenantDateRange, WithMediaCollection;

    // Model instance
    public $modelInstance = ContentItem::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Content',
            'field' => 'contents.name',
        ],
        [
            'name' => 'Item',
            'field' => 'content_items.name',
        ],
        [
            'name' => 'Description',
            'field' => 'content_items.description',
        ],
        [
            'name' => 'Status',
            'field' => 'content_items.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'content_items.created_at',
        ],
    ];

    public $contents = [];

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'content_items.created_at';

        // Load tenants for tenant selection if super admin
        if (auth()->user()->isSuperAdmin()) {
            // Get all active tenants
            $this->fetchAllActiveTenants();
        }
    }

    public function getContents()
    {
        if ($this->tenant_id) {
            $this->contents = Content::query()
                ->where('tenant_id', $this->tenant_id)
                ->where('status', CommonStatusEnum::ACTIVE)
                ->get();
        }
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = ContentItem::query()
            ->join('tenants', 'tenants.id', '=', 'content_items.tenant_id')
            ->join('contents', 'contents.id', '=', 'content_items.content_id')
            ->select(
                'content_items.*',
                'contents.name as content_name',
                'tenants.name as tenant_name',
            );

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('content_items.tenant_id', auth()->user()->tenant?->tenant_id);
        } else {
            $model = $this->applyFilterTenantDateRange(
                model: $model,
                tenantField: 'content_items.tenant_id',
                dateField: 'content_items.created_at',
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

    public $content_id;

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

        $record = ContentItem::find($id);
        $this->oldData = $record;
        $this->recordId = $record->id;
        $this->tenant_id = $record->tenant_id;
        $this->content_id = $record->content_id;
        $this->name = $record->name;
        $this->description = $record->description;
        $this->status = $record->status->value;

        // Get contents for the tenant
        $this->getContents();

        // Set content_id value in the frontend
        $this->dispatch('setValueById', id: 'content_id', value: $this->content_id);
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'oldData',
            'tenant_id',
            'content_id',
            'name',
            'description',
            'status',
            'image',
        ]);

        $this->tenant_id = auth()->user()->tenant?->tenant_id;
        $this->status = CommonStatusEnum::ACTIVE->value;

        // Get contents for the tenant
        $this->getContents();
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'content_id' => 'required|exists:contents,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
            // Max 50MB
            'image' => 'nullable|image|max:51200',
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
        $model->version = Content::max('version') + 1;
        $model->save();

        $this->resetRecordData();
    }
};
