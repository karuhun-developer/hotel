<?php

use App\Actions\Cms\M3u\Source\StoreM3uSourceAction;
use App\Actions\Cms\M3u\Source\UpdateM3uSourceAction;
use App\Enums\CommonStatusEnum;
use App\Models\M3u\M3uSource;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    // Model instance
    public $modelInstance = M3uSource::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $name;

    public $url;

    public $type = 'GET';

    public $headers;

    public $body;

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

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = M3uSource::findOrFail($id);
        $this->fill(
            $record->only(
                'id',
                'name',
                'url',
                'type',
                'headers',
                'body',
            )
        );
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'id',
            'name',
            'url',
            'headers',
            'body',
        ]);
        $this->type = 'GET';
        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    // Handle form submit
    public function submit(StoreM3uSourceAction $storeAction, UpdateM3uSourceAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'type' => 'required|in:GET,POST',
            'headers' => 'nullable|string',
            'body' => 'nullable|string',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
        ]);

        $data = $this->only(['name', 'url', 'type', 'headers', 'body', 'status']);

        if ($this->isUpdate) {
            $updateAction->handle(
                m3uSource: M3uSource::findOrFail($this->id),
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
            message: $this->isUpdate ? 'M3U Source updated successfully.' : 'M3U Source created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
