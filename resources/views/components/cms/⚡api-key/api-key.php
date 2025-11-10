<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\User\ApiKey;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = ApiKey::class;

    // Latest api keys generated
    public $latestApiKey;

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }
    }

    public function render()
    {
        return $this->view([
            'data' => auth()->user()->apiKeys,
        ]);
    }

    // Record data
    public $name;

    public function resetRecordData() {}

    // Generate new api key
    public function generateApiKey()
    {
        // Check if has permission
        if (! auth()->user()->can('create'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        // Create a new API key
        $salt = Str::password(15);

        // Save the API key to the user
        $apiKey = auth()->user()->apiKeys()->create([
            'name' => $this->name,
            'salt' => bcrypt($salt),
            'expired_at' => null,
            'last_used_at' => null,
            'status' => CommonStatusEnum::ACTIVE,
        ]);

        $apiKey = [
            'id' => $apiKey->id,
            'salt' => $salt,
        ];

        $this->latestApiKey = Crypt::encrypt($apiKey);

        $this->dispatch('toast', type: 'success', message: 'API Key generated successfully. Please copy your new API key now as it won\'t be shown again.');

        // Close modal
        $this->closeModal();
    }

    #[On('regenerateApiKey')]
    public function regenerateApiKey($id)
    {
        // Find the API key
        $apiKey = auth()->user()->apiKeys()->find($id);

        // Create a new API key
        $salt = Str::password(15);

        $apiKey->update([
            'salt' => bcrypt($salt),
        ]);

        $this->latestApiKey = Crypt::encrypt([
            'id' => $id,
            'salt' => $salt,
        ]);

        $this->dispatch('toast', type: 'success', message: 'API Key regenerated successfully. Please copy your new API key now as it won\'t be shown again.');
    }
};
