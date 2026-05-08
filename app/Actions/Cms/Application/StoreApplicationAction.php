<?php

namespace App\Actions\Cms\Application;

use App\Models\Tenant\Application;

class StoreApplicationAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Application
    {
        return Application::create($data);
    }
}
