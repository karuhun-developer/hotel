<?php

namespace App\Actions\Cms\Tenant;

use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantProfile;

class UpdateTenantProfileAction
{
    /**
     * Handle the action.
     */
    public function handle(Tenant $tenant, array $data): TenantProfile
    {
        $profile = $tenant->profile;

        if ($profile) {
            $profile->update($data);
        } else {
            $profile = $tenant->profile()->create($data);
        }

        return $profile;
    }
}
