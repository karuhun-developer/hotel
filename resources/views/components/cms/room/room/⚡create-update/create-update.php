<?php

use App\Actions\Cms\Room\Room\StoreRoomAction;
use App\Actions\Cms\Room\Room\UpdateRoomAction;
use App\Enums\CommonStatusEnum;
use App\Models\Tenant\Room;
use App\Models\Tenant\RoomType;
use App\Models\Tenant\Tenant;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // Model instance
    public $modelInstance = Room::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $tenant_id;

    public $room_type_id;

    public $no;

    public $guest_name;

    public $greeting;

    public $device_name;

    public $is_birthday = false;

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

    #[Computed]
    public function roomTypes()
    {
        return RoomType::when($this->tenant_id, function ($query) {
            $query->where('tenant_id', $this->tenant_id);
        })->get();
    }

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = Room::findOrFail($id);
        $this->fill(
            $record->only(
                'id',
                'tenant_id',
                'room_type_id',
                'no',
                'guest_name',
                'greeting',
                'device_name',
                'is_birthday',
            )
        );
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'id',
            'tenant_id',
            'room_type_id',
            'no',
            'guest_name',
            'greeting',
            'device_name',
            'is_birthday',
        ]);
        $this->tenant_id = auth()->user()->tenant?->tenant_id;
    }

    // Handle form submit
    public function submit(StoreRoomAction $storeAction, UpdateRoomAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_type_id' => 'required|exists:room_types,id',
            'no' => 'required|string|max:255',
            'guest_name' => 'nullable|string|max:255',
            'greeting' => 'nullable|string|max:255',
            'device_name' => 'nullable|string|max:255',
            'is_birthday' => 'boolean',
        ]);

        $data = $this->only(['tenant_id', 'room_type_id', 'no', 'guest_name', 'greeting', 'device_name', 'is_birthday']);

        if ($this->isUpdate) {
            $updateAction->handle(
                room: Room::findOrFail($this->id),
                data: $data,
            );
        } else {
            $storeAction->handle(
                data: $data,
            );
        }

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'Room updated successfully.' : 'Room created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
