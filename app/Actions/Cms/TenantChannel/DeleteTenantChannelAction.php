<?php

namespace App\Actions\Cms\TenantChannel;

use App\Models\Tenant\TenantChannel;

class DeleteTenantChannelAction
{
    /**
     * Handle the action.
     */
    public function handle(TenantChannel $tenantChannel): bool
    {
        return $tenantChannel->delete();
    }
}
