<?php

use App\Actions\Cms\ApiKey\DeleteApiKeyAction;
use App\Actions\Cms\ApiKey\RegenerateApiKeyAction;
use App\Livewire\BaseComponent;
use App\Models\User\ApiKey;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = ApiKey::class;

    public $latestApiKey;

    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'api_keys.name',
        ],
        [
            'name' => 'Status',
            'field' => 'api_keys.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'api_keys.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);
        $this->paginationOrderBy = 'api_keys.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        $model = ApiKey::query()
            ->where('user_id', auth()->id());

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
    public function delete($id, DeleteApiKeyAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $apiKey = auth()->user()->apiKeys()->findOrFail($id);
        $deleteAction->handle($apiKey);

        $this->dispatch('toast', type: 'success', message: 'API Key deleted successfully.');
    }

    #[On('regenerateApiKey')]
    public function regenerateApiKey($id, RegenerateApiKeyAction $regenerateAction)
    {
        Gate::authorize('update'.$this->modelInstance);

        $apiKey = auth()->user()->apiKeys()->findOrFail($id);
        $this->latestApiKey = $regenerateAction->handle($apiKey);

        $this->dispatch('toast', type: 'success', message: 'API Key regenerated successfully. Please copy your new API key now as it won\'t be shown again.');
    }
};
