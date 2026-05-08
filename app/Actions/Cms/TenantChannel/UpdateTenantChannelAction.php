<?php

namespace App\Actions\Cms\TenantChannel;

use App\Models\Tenant\TenantChannel;

class UpdateTenantChannelAction
{
    /**
     * Handle the action.
     */
    public function handle(TenantChannel $tenantChannel, array $data): bool
    {
        return $tenantChannel->update($data);
    }
}
