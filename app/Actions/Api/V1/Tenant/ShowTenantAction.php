<?php

namespace App\Actions\Api\V1\Tenant;

use App\Models\Tenant\Tenant;

class ShowTenantAction
{
    /**
     * Handle the action.
     */
    public function handle(int $tenantId): Tenant
    {
        $model = Tenant::with('profile.media')->findOrFail($tenantId);

        if ($model->profile) {
            $model->profile->logo_color = $model->profile->getFirstMediaUrl('logo_color');
            $model->profile->logo_white = $model->profile->getFirstMediaUrl('logo_white');
            $model->profile->logo_black = $model->profile->getFirstMediaUrl('logo_black');
            $model->profile->main_photo = $model->profile->getFirstMediaUrl('main_photo');
            $model->profile->background_photo = $model->profile->getFirstMediaUrl('background_photo');
            $model->profile->intro_video = $model->profile->getFirstMediaUrl('intro_video');
        }

        return $model;
    }
}
