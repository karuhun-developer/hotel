<?php

use App\Actions\Cms\Application\StoreApplicationAction;
use App\Actions\Cms\Application\UpdateApplicationAction;
use App\Enums\CommonStatusEnum;
use App\Models\Tenant\Application;
use App\Models\Tenant\Tenant;
use App\Traits\WithMediaCollection;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads, WithMediaCollection;

    // Model instance
    public $modelInstance = Application::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $tenant_id;

    public $name;

    public $package_name;

    public $image;

    public $record = null;

    #[On('set-action')]
    public function setAction($id = null)
    {
        if ($id) {
            $this->isUpdate = true;
            $this->getRecordData($id);
        } else {
            $this->isUpdate = false;
            $this->resetRecordData();
        }
    }

    #[Computed]
    public function tenants()
    {
        return Tenant::where('status', CommonStatusEnum::ACTIVE)->get();
    }

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = Application::findOrFail($id);
        $this->record = $record;
        $this->fill(
            $record->only(
                'id',
                'tenant_id',
                'name',
                'package_name',
            )
        );
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'id',
            'record',
            'tenant_id',
            'name',
            'package_name',
            'image',
        ]);
        $this->tenant_id = auth()->user()->tenant?->tenant_id;
    }

    // Handle form submit
    public function submit(StoreApplicationAction $storeAction, UpdateApplicationAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'image' => 'nullable|image:allow_svg|max:51200',
        ]);

        $data = $this->only(['tenant_id', 'name', 'package_name']);

        if ($this->isUpdate) {
            $application = Application::findOrFail($this->id);
            $updateAction->handle(
                application: $application,
                data: $data,
            );
        } else {
            $application = $storeAction->handle(
                data: $data,
            );
        }

        // Handle image upload
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->saveMedia(model: $application, file: $this->image, collection: 'image');
        }

        // Reset file input
        $this->reset('image');
        $this->record = null;

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'Application updated successfully.' : 'Application created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
