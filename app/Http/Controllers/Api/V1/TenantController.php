<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\Tenant\ShowTenantAction;
use App\Http\Controllers\Controller;

class TenantController extends Controller
{
    /**
     * Display the tenant info.
     */
    public function show(ShowTenantAction $action)
    {
        $data = $action->handle(auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }
}
