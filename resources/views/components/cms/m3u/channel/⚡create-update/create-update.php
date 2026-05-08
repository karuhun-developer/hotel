<?php

use App\Actions\Cms\M3u\Channel\StoreM3uChannelAction;
use App\Actions\Cms\M3u\Channel\UpdateM3uChannelAction;
use App\Enums\CommonStatusEnum;
use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
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
    public $modelInstance = M3uChannel::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $m3u_source_id;

    public $name;

    public $alias;

    public $url;

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
    public function sources()
    {
        return M3uSource::where('status', CommonStatusEnum::ACTIVE)->get();
    }

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = M3uChannel::findOrFail($id);
        $this->record = $record;
        $this->fill(
            $record->only(
                'id',
                'm3u_source_id',
                'name',
                'alias',
                'url',
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
            'm3u_source_id',
            'name',
            'alias',
            'url',
            'image',
        ]);
        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    // Handle form submit
    public function submit(StoreM3uChannelAction $storeAction, UpdateM3uChannelAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'm3u_source_id' => 'required|exists:m3u_sources,id',
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'url' => 'required|string',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
            'image' => 'nullable|image:allow_svg|max:51200',
        ]);

        $data = $this->only(['m3u_source_id', 'name', 'alias', 'url', 'status']);

        if ($this->isUpdate) {
            $m3uChannel = M3uChannel::findOrFail($this->id);
            $updateAction->handle(
                m3uChannel: $m3uChannel,
                data: $data,
            );
        } else {
            $m3uChannel = $storeAction->handle(
                data: $data,
            );
        }

        // Handle image upload
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->saveMedia(model: $m3uChannel, file: $this->image, collection: 'image');
        }

        // Reset file input
        $this->reset('image');
        $this->record = null;

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'M3U Channel updated successfully.' : 'M3U Channel created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
