<?php

namespace App\Actions\Cms\Tenant;

use App\Models\Tenant\Tenant;

class DeleteTenantAction
{
    /**
     * Handle the action.
     */
    public function handle(Tenant $tenant): bool
    {
        return $tenant->delete();
    }
}
