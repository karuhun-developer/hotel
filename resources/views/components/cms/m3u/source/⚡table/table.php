<?php

use App\Actions\Cms\M3u\Source\DeleteM3uSourceAction;
use App\Actions\Cms\M3u\Source\FetchChannelsAction;
use App\Livewire\BaseComponent;
use App\Models\M3u\M3uSource;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = M3uSource::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'm3u_sources.name',
        ],
        [
            'name' => 'URL',
            'field' => 'm3u_sources.url',
        ],
        [
            'name' => 'Type',
            'field' => 'm3u_sources.type',
        ],
        [
            'name' => 'Status',
            'field' => 'm3u_sources.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'm3u_sources.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'm3u_sources.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = M3uSource::withCount('channels');

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
    public function delete($id, DeleteM3uSourceAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            m3uSource: M3uSource::findOrFail($id),
        );

        $this->dispatch('toast', type: 'success', message: 'M3U Source deleted successfully.');
    }

    #[On('fetchChannels')]
    public function fetchChannels($id, FetchChannelsAction $fetchAction)
    {
        Gate::authorize('update'.$this->modelInstance);

        $m3uSource = M3uSource::findOrFail($id);

        try {
            $synced = $fetchAction->handle($m3uSource);

            $this->dispatch('toast', type: 'success', message: 'Fetched and synced '.$synced.' channels successfully.');
        } catch (Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Fetch failed: '.$e->getMessage());
        }
    }
};
