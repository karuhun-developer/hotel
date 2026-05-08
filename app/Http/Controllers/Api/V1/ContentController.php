<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\Content\ChangeListContentAction;
use App\Actions\Api\V1\Content\ChangeListContentItemAction;
use App\Actions\Api\V1\Content\IndexContentAction;
use App\Actions\Api\V1\Content\IndexContentItemAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * List contents.
     */
    public function contents(Request $request, IndexContentAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * Contents change list.
     */
    public function contentsChangeList(Request $request, ChangeListContentAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * List content items.
     */
    public function contentItems(Request $request, IndexContentItemAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * Content items change list.
     */
    public function contentItemChangeList(Request $request, ChangeListContentItemAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }
}
