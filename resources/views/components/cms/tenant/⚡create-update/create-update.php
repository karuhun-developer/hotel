<?php

use App\Actions\Cms\Tenant\StoreTenantAction;
use App\Actions\Cms\Tenant\UpdateTenantAction;
use App\Enums\CommonStatusEnum;
use App\Enums\TenantTypeEnum;
use App\Models\Tenant\Tenant;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $modelInstance = Tenant::class;

    public $isUpdate = false;

    public $id;

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

    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = Tenant::findOrFail($id);
        $this->fill($record->only('id', 'name', 'branch', 'address', 'phone', 'email', 'website', 'default_greeting', 'password_setting'));
        $this->type = $record->type->value;
        $this->status = $record->status->value;
    }

    public function resetRecordData()
    {
        $this->reset(['id', 'name', 'type', 'branch', 'address', 'phone', 'email', 'website', 'default_greeting', 'password_setting', 'status']);
        $this->type = TenantTypeEnum::HOTEL->value;
        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    public function submit(StoreTenantAction $storeAction, UpdateTenantAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:'.implode(',', TenantTypeEnum::toArray()),
            'branch' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'website' => 'required|url|max:255',
            'default_greeting' => 'nullable|string|max:1000',
            'password_setting' => 'required|string|max:255',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
        ]);

        $data = $this->only(['name', 'type', 'branch', 'address', 'phone', 'email', 'website', 'default_greeting', 'password_setting', 'status']);

        if ($this->isUpdate) {
            $updateAction->handle(Tenant::findOrFail($this->id), $data);
        } else {
            $storeAction->handle($data);
        }

        $this->dispatch('toast', type: 'success', message: $this->isUpdate ? 'Tenant updated successfully.' : 'Tenant created successfully.');
        $this->dispatch('reset-parent-page');
        Flux::modal('defaultModal')->close();
    }
};
