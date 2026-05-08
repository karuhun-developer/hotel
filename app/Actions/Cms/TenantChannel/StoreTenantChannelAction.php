<?php

namespace App\Actions\Cms\TenantChannel;

use App\Models\Tenant\TenantChannel;

class StoreTenantChannelAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): TenantChannel
    {
        return TenantChannel::create($data);
    }
}
