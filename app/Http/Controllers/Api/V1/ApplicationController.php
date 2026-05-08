<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\Application\ChangeListApplicationAction;
use App\Actions\Api\V1\Application\IndexApplicationAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * List applications.
     */
    public function index(Request $request, IndexApplicationAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * Applications change list.
     */
    public function changeList(Request $request, ChangeListApplicationAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }
}
