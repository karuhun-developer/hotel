<?php

namespace App\Actions\Api\V1\Room;

use App\Models\Tenant\RoomType;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class IndexRoomTypeAction
{
    use WithGetFilterDataApi;

    /**
     * Handle the action.
     */
    public function handle(Request $request, int $tenantId)
    {
        $model = RoomType::query()
            ->with('media')
            ->where('tenant_id', $tenantId)
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            });

        $data = $this->getDataWithFilter(
            model: $model,
            searchBy: ['name', 'description'],
            orderBy: $request?->orderBy ?? 'name',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );

        $data->each(function ($item) {
            $item->image = $item->getFirstMediaUrl('image');
        });

        return $data;
    }
}
