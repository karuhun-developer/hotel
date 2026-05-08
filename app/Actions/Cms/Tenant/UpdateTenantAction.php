<?php

namespace App\Actions\Cms\Tenant;

use App\Models\Tenant\Tenant;

class UpdateTenantAction
{
    /**
     * Handle the action.
     */
    public function handle(Tenant $tenant, array $data): bool
    {
        return $tenant->update($data);
    }
}
