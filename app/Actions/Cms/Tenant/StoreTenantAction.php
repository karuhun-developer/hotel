<?php

namespace App\Actions\Cms\Tenant;

use App\Models\Tenant\Tenant;

class StoreTenantAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Tenant
    {
        $tenant = Tenant::create($data);

        // Create default profile
        $tenant->profile()->create([
            'running_text' => 'Welcome to '.$tenant->name,
            'welcome_text' => 'Welcome to '.$tenant->name.'! We are glad to have you here.',
        ]);

        return $tenant;
    }
}
