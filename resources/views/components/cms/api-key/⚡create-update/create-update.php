<?php

use App\Actions\Cms\ApiKey\StoreApiKeyAction;
use App\Models\User\ApiKey;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $modelInstance = ApiKey::class;

    public $name = '';

    #[On('set-action')]
    public function setAction()
    {
        $this->reset('name');
    }

    public function submit(StoreApiKeyAction $storeAction)
    {
        Gate::authorize('create'.$this->modelInstance);

        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $result = $storeAction->handle(
            user: auth()->user(),
            name: $this->name,
        );

        $this->dispatch('toast', type: 'success', message: 'API Key generated successfully. Please copy your new API key now as it won\'t be shown again.');
        $this->dispatch('reset-parent-page');
        $this->dispatch('apiKeyGenerated', key: $result['plainTextKey']);

        Flux::modal('defaultModal')->close();
    }
};
