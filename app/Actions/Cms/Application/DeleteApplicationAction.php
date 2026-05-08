<?php

namespace App\Actions\Cms\Application;

use App\Models\Tenant\Application;

class DeleteApplicationAction
{
    /**
     * Handle the action.
     */
    public function handle(Application $application): bool
    {
        $application->version = Application::max('version') + 1;
        $application->save();

        return $application->delete();
    }
}
