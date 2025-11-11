<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\Tenant\RoomType;
use App\Models\Tenant\Tenant;
use App\Traits\WithMediaCollection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends BaseComponent
{
    use WithFileUploads, WithMediaCollection;

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

    public $tenants = [];

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'room_types.created_at';

        // Load tenants for tenant selection if super admin
        if (auth()->user()->isSuperAdmin()) {
            $this->tenants = Tenant::where('status', CommonStatusEnum::ACTIVE)->get();
        }
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = RoomType::query()
            ->join('tenants', 'tenants.id', '=', 'room_types.tenant_id')
            ->select(
                'room_types.*',
                'tenants.name as tenant_name',
            )
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('room_types.tenant_id', auth()->user()->tenant?->tenant_id);
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

    public $image;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to view this record.');

            return;
        }

        $record = RoomType::find($id);
        $this->oldData = $record;
        $this->recordId = $record->id;
        $this->tenant_id = $record->tenant_id;
        $this->name = $record->name;
        $this->description = $record->description;
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
            'image',
        ]);

        $this->tenant_id = auth()->user()->tenant?->tenant_id;
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
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

        $this->resetRecordData();
    }
};
