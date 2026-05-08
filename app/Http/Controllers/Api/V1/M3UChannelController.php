<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\M3u\IndexM3uChannelAction;
use App\Http\Controllers\Controller;

class M3UChannelController extends Controller
{
    /**
     * List M3U channels.
     */
    public function index(IndexM3uChannelAction $action)
    {
        $data = $action->handle(auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }
}
