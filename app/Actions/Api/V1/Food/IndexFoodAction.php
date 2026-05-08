<?php

namespace App\Actions\Api\V1\Food;

use App\Models\Tenant\Food\Food;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class IndexFoodAction
{
    use WithGetFilterDataApi;

    /**
     * Handle the action.
     */
    public function handle(Request $request, int $tenantId)
    {
        $model = Food::query()
            ->with('media')
            ->where('tenant_id', $tenantId)
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->has('food_category_id'), function ($query) use ($request) {
                $query->where('food_category_id', $request->food_category_id);
            });

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;
            $model->whereIn('id', $ids);
        }

        $data = $this->getDataWithFilter(
            model: $model,
            searchBy: ['name', 'price', 'description'],
            orderBy: $request?->orderBy ?? 'created_at',
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
