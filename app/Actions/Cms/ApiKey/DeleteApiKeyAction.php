<?php

namespace App\Actions\Cms\ApiKey;

use App\Models\User\ApiKey;

class DeleteApiKeyAction
{
    /**
     * Handle the action.
     */
    public function handle(ApiKey $apiKey): bool
    {
        return $apiKey->delete();
    }
}
