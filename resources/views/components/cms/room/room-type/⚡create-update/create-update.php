<?php

use App\Actions\Cms\Room\RoomType\StoreRoomTypeAction;
use App\Actions\Cms\Room\RoomType\UpdateRoomTypeAction;
use App\Enums\CommonStatusEnum;
use App\Models\Tenant\RoomType;
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
    public $modelInstance = RoomType::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $tenant_id;

    public $name;

    public $description;

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

        $record = RoomType::findOrFail($id);
        $this->record = $record;
        $this->fill(
            $record->only(
                'id',
                'tenant_id',
                'name',
                'description',
            )
        );
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->record = null;
        $this->reset([
            'id',
            'tenant_id',
            'name',
            'description',
            'image',
        ]);
        $this->tenant_id = auth()->user()->tenant?->tenant_id;
    }

    // Handle form submit
    public function submit(StoreRoomTypeAction $storeAction, UpdateRoomTypeAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image:allow_svg|max:51200',
        ]);

        $data = $this->only(['tenant_id', 'name', 'description']);

        if ($this->isUpdate) {
            $roomType = RoomType::findOrFail($this->id);
            $updateAction->handle(
                roomType: $roomType,
                data: $data,
            );
        } else {
            $roomType = $storeAction->handle(
                data: $data,
            );
        }

        // Handle image upload
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->saveMedia(model: $roomType, file: $this->image, collection: 'images');
        }

        // Reset file input
        $this->reset('image');
        $this->record = null;

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'Room type updated successfully.' : 'Room type created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
