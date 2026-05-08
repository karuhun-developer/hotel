<?php

use App\Actions\Cms\FrontDesk\StoreFrontDeskAction;
use App\Actions\Cms\FrontDesk\UpdateFrontDeskAction;
use App\Enums\CommonStatusEnum;
use App\Models\Tenant\FrontDesk;
use App\Models\Tenant\Room;
use App\Models\Tenant\Tenant;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // Model instance
    public $modelInstance = FrontDesk::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $tenant_id;

    public $room_id;

    public $guest_name;

    public $check_in;

    public $check_out;

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
    public function rooms()
    {
        return Room::when($this->tenant_id, function ($query) {
            $query->where('tenant_id', $this->tenant_id);
        })->get();
    }

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = FrontDesk::findOrFail($id);
        $this->fill(
            $record->only(
                'id',
                'tenant_id',
                'room_id',
                'guest_name',
                'check_in',
                'check_out',
            )
        );
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'id',
            'tenant_id',
            'room_id',
            'guest_name',
            'check_in',
            'check_out',
        ]);
        $this->tenant_id = auth()->user()->tenant?->tenant_id;
    }

    // Handle form submit
    public function submit(StoreFrontDeskAction $storeAction, UpdateFrontDeskAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date',
        ]);

        $data = $this->only(['tenant_id', 'room_id', 'guest_name', 'check_in', 'check_out']);

        if ($this->isUpdate) {
            $updateAction->handle(
                frontDesk: FrontDesk::findOrFail($this->id),
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
            message: $this->isUpdate ? 'Front Desk updated successfully.' : 'Front Desk created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
