<?php

namespace App\Actions\Cms\Application;

use App\Models\Tenant\Application;

class UpdateApplicationAction
{
    /**
     * Handle the action.
     */
    public function handle(Application $application, array $data): bool
    {
        return $application->update($data);
    }
}
