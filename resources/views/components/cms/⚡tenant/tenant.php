<?php

use App\Enums\CommonStatusEnum;
use App\Enums\TenantTypeEnum;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantProfile;
use App\Traits\WithMediaCollection;
use Flux\Flux;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends BaseComponent
{
    use WithFileUploads, WithMediaCollection;

    // Model instance
    public $modelInstance = Tenant::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'name',
        ],
        [
            'name' => 'Type',
            'field' => 'type',
        ],
        [
            'name' => 'Branch',
            'field' => 'branch',
        ],
        [
            'name' => 'Address',
            'field' => 'address',
        ],
        [
            'name' => 'Phone',
            'field' => 'phone',
        ],
        [
            'name' => 'Email',
            'field' => 'email',
        ],
        [
            'name' => 'Website',
            'field' => 'website',
        ],
        [
            'name' => 'Default Greeting',
            'field' => 'default_greeting',
        ],
        [
            'name' => 'Password Setting',
            'field' => 'password_setting',
        ],
        [
            'name' => 'Status',
            'field' => 'status',
        ],
    ];

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
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

    // Tenant Record data
    public $recordId;

    public $name;

    public $type;

    public $branch;

    public $address;

    public $phone;

    public $email;

    public $website;

    public $default_greeting;

    public $password_setting;

    public $status;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to view this record.');

            return;
        }

        $record = Tenant::find($id);
        $this->recordId = $record->id;
        $this->name = $record->name;
        $this->type = $record->type->value;
        $this->branch = $record->branch;
        $this->address = $record->address;
        $this->phone = $record->phone;
        $this->email = $record->email;
        $this->website = $record->website;
        $this->default_greeting = $record->default_greeting;
        $this->password_setting = $record->password_setting;
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'name',
            'type',
            'branch',
            'address',
            'phone',
            'email',
            'website',
            'default_greeting',
            'password_setting',
            'status',
        ]);

        $this->type = TenantTypeEnum::HOTEL->value;
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'website' => 'required|url|max:255',
            'default_greeting' => 'nullable|string|max:1000',
            'password_setting' => 'required|string|max:255',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
        ]);

        $record = $this->save();

        // Create record for profile tenant
        if (! $record->profile) {
            $record->profile()->create([
                'running_text' => 'Welcome to '.$record->name,
                'welcome_text' => 'Welcome to '.$record->name.'! We are glad to have you here.',
            ]);
        }
    }

    // Properties for tenant profile
    public $tenant_id;

    public $tenant;

    public $profile;

    public $running_text;

    public $primary_color;

    public $description;

    public $welcome_text;

    public $logo_color;

    public $logo_white;

    public $logo_black;

    public $main_photo;

    public $background_photo;

    public $intro_video;

    public function getProfile(Tenant $tenant)
    {
        $this->resetProfile();
        $this->tenant_id = $tenant->id;
        if ($tenant->profile) {
            $this->tenant = $tenant;
            $this->profile = $tenant->profile;
            $this->running_text = $tenant->profile->running_text;
            $this->primary_color = $tenant->profile->primary_color;
            $this->description = $tenant->profile->description;
            $this->welcome_text = $tenant->profile->welcome_text;
        }

        Flux::modal('profileModal')->show();
    }

    public function resetProfile()
    {
        $this->reset([
            'tenant',
            'profile',
            'tenant_id',
            'running_text',
            'primary_color',
            'description',
            'welcome_text',
            'logo_color',
            'logo_white',
            'logo_black',
            'main_photo',
            'background_photo',
            'intro_video',
        ]);
    }

    public function submitProfile()
    {
        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'running_text' => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:7',
            'description' => 'nullable|string|max:2000',
            'welcome_text' => 'nullable|string|max:2000',
            // Max 50MB for images and videos
            'logo_color' => 'nullable|image|max:51200',
            'logo_white' => 'nullable|image|max:51200',
            'logo_black' => 'nullable|image|max:51200',
            'main_photo' => 'nullable|image|max:51200',
            'background_photo' => 'nullable|image|max:51200',
            'intro_video' => 'nullable|file|mimes:mp4,ogx,oga,ogv,ogg,webm,mkv|max:51200',
        ]);

        // Create or update tenant profile
        $model = TenantProfile::where('tenant_id', $this->tenant_id)->first();

        if ($model) {
            $model->update($this->all());
        } else {
            $model->create($this->all());
        }

        // Handle file uploads
        if ($this->logo_color instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->logo_color,
                collection: 'logo_color',
            );
        }
        if ($this->logo_white instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->logo_white,
                collection: 'logo_white',
            );
        }
        if ($this->logo_black instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->logo_black,
                collection: 'logo_black',
            );
        }
        if ($this->main_photo instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->main_photo,
                collection: 'main_photo',
            );
        }
        if ($this->background_photo instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->background_photo,
                collection: 'background_photo',
            );
        }
        if ($this->intro_video instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->intro_video,
                collection: 'intro_video',
            );
        }

        // Alert success
        $this->dispatch('toast', type: 'success', message: 'Tenant profile saved successfully.');

        $this->closeProfileModal();
    }

    public function closeProfileModal()
    {
        $this->resetProfile();
        Flux::modal('profileModal')->close();
    }
};
