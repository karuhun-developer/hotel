<?php

namespace App\Actions\Api\V1\Room;

use App\Models\Tenant\Room;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class IndexRoomAction
{
    use WithGetFilterDataApi;

    /**
     * Handle the action.
     */
    public function handle(Request $request, int $tenantId)
    {
        $model = Room::query()
            ->where('tenant_id', $tenantId)
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('room_type_id'), function ($query) use ($request) {
                $query->where('room_type_id', $request->room_type_id);
            });

        return $this->getDataWithFilter(
            model: $model,
            searchBy: ['no', 'guest_name', 'greeting', 'device_name'],
            orderBy: $request?->orderBy ?? 'no',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );
    }
}
