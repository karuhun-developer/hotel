<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Tenant;

class TenantController extends Controller
{
    public function show() {
        $model = Tenant::with('profile.media')->findOrFail(auth()->user()->tenant?->tenant_id);

        // If profile exists, load media
        if ($model->profile) {
            $model->profile->logo_color = $model->profile->getFirstMediaUrl('logo_color');
            $model->profile->logo_white = $model->profile->getFirstMediaUrl('logo_white');
            $model->profile->logo_black = $model->profile->getFirstMediaUrl('logo_black');
            $model->profile->main_photo = $model->profile->getFirstMediaUrl('main_photo');
            $model->profile->background_photo = $model->profile->getFirstMediaUrl('background_photo');
            $model->profile->intro_video = $model->profile->getFirstMediaUrl('intro_video');
        }

        return $this->responseWithSuccess($model);
    }
}
