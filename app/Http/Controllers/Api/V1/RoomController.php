<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\Room\IndexRoomAction;
use App\Actions\Api\V1\Room\IndexRoomTypeAction;
use App\Actions\Api\V1\Room\ShowRoomAction;
use App\Actions\Api\V1\Room\ShowRoomTypeAction;
use App\Http\Controllers\Controller;
use App\Models\Tenant\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * List room types.
     */
    public function types(Request $request, IndexRoomTypeAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * Show a room type.
     */
    public function typeShow(RoomType $model, ShowRoomTypeAction $action)
    {
        $data = $action->handle($model);

        return $this->responseWithSuccess($data);
    }

    /**
     * List rooms.
     */
    public function items(Request $request, IndexRoomAction $action)
    {
        $data = $action->handle($request, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }

    /**
     * Show a room by number.
     */
    public function itemShow($no, ShowRoomAction $action)
    {
        $data = $action->handle($no, auth()->user()->tenant?->tenant_id);

        return $this->responseWithSuccess($data);
    }
}
