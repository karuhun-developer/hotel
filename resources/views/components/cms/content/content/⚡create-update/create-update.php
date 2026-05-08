<?php

use App\Actions\Cms\Content\Content\StoreContentAction;
use App\Actions\Cms\Content\Content\UpdateContentAction;
use App\Enums\CommonStatusEnum;
use App\Models\Tenant\Content\Content;
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
    public $modelInstance = Content::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $tenant_id;

    public $name;

    public $status;

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

        $record = Content::findOrFail($id);
        $this->record = $record;
        $this->fill(
            $record->only(
                'id',
                'tenant_id',
                'name',
            )
        );
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->record = null;
        $this->reset([
            'id',
            'tenant_id',
            'name',
            'status',
            'image',
        ]);
        $this->tenant_id = auth()->user()->tenant?->tenant_id;
        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    // Handle form submit
    public function submit(StoreContentAction $storeAction, UpdateContentAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
            'image' => 'nullable|image:allow_svg|max:51200',
        ]);

        $data = $this->only(['tenant_id', 'name', 'status']);

        if ($this->isUpdate) {
            $content = Content::findOrFail($this->id);
            $updateAction->handle(
                content: $content,
                data: $data,
            );
        } else {
            $content = $storeAction->handle(
                data: $data,
            );
        }

        // Handle image upload
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->saveMedia(model: $content, file: $this->image, collection: 'image');
        }

        // Reset file input
        $this->reset('image');
        $this->record = null;

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'Content updated successfully.' : 'Content created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
