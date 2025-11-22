<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Room;
use App\Models\Tenant\RoomType;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use WithGetFilterDataApi;

    public function types(Request $request)
    {
        $model = RoomType::query()
            ->with('media')
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            });

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'name',
                'description',
            ],
            orderBy: $request?->orderBy ?? 'package_name',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );

        // Load images
        $model->each(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');
        });

        return $this->responseWithSuccess($model);
    }

    public function typeShow(RoomType $model)
    {
        // Load image
        $model->image = $model->getFirstMediaUrl('image');

        return $this->responseWithSuccess($model);
    }

    public function items(Request $request)
    {
        $model = Room::query()
            ->where('tenant_id', auth()->user()->tenant?->tenant_id)
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('room_type_id'), function ($query) use ($request) {
                $query->where('room_type_id', $request->room_type_id);
            });

        $model = $this->getDataWithFilter(
            model: $model,
            searchBy: [
                'no',
                'guest_name',
                'greeting',
                'device_name',
            ],
            orderBy: $request?->orderBy ?? 'package_name',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );

        return $this->responseWithSuccess($model);
    }

    public function itemShow($no)
    {
        $model = Room::where('tenant_id', auth()->user()->tenant?->tenant_id)->where('no', $no)->firstOrFail();

        return $this->responseWithSuccess($model);
    }
}
