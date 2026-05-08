<?php

use App\Actions\Cms\M3u\Channel\DeleteM3uChannelAction;
use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = M3uChannel::class;

    public $sourceId = null;

    #[Url(as: 'source', except: '')]
    public $sourceFilter = '';

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Source',
            'field' => 'm3u_sources.name',
        ],
        [
            'name' => 'Name',
            'field' => 'm3u_channels.name',
        ],
        [
            'name' => 'Alias',
            'field' => 'm3u_channels.alias',
        ],
        [
            'name' => 'Status',
            'field' => 'm3u_channels.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'm3u_channels.created_at',
        ],
    ];

    #[Computed]
    public function sources()
    {
        return M3uSource::where('status', CommonStatusEnum::ACTIVE)->get();
    }

    public function mount($sourceId = null)
    {
        Gate::authorize('view'.$this->modelInstance);

        $this->sourceId = $sourceId;

        // If mounted with sourceId, set filter
        if ($this->sourceId) {
            $this->sourceFilter = $this->sourceId;
        }

        $this->paginationOrderBy = 'm3u_channels.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = M3uChannel::query()
            ->join('m3u_sources', 'm3u_sources.id', '=', 'm3u_channels.m3u_source_id')
            ->select('m3u_channels.*', 'm3u_sources.name as source_name')
            ->with('media')
            ->when($this->sourceFilter, function ($query) {
                $query->where('m3u_channels.m3u_source_id', $this->sourceFilter);
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

    #[On('delete')]
    public function delete($id, DeleteM3uChannelAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            m3uChannel: M3uChannel::findOrFail($id),
        );

        $this->dispatch('toast', type: 'success', message: 'M3U Channel deleted successfully.');
    }

    #[On('toggleStatus')]
    public function toggleStatus($id)
    {
        Gate::authorize('update'.$this->modelInstance);

        $record = M3uChannel::findOrFail($id);
        $record->status = $record->status === CommonStatusEnum::ACTIVE
            ? CommonStatusEnum::INACTIVE
            : CommonStatusEnum::ACTIVE;
        $record->save();

        $this->dispatch('toast', type: 'success', message: 'Status updated successfully.');
    }
};
