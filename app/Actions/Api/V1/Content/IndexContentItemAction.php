<?php

namespace App\Actions\Api\V1\Content;

use App\Models\Tenant\Content\ContentItem;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class IndexContentItemAction
{
    use WithGetFilterDataApi;

    /**
     * Handle the action.
     */
    public function handle(Request $request, int $tenantId)
    {
        $model = ContentItem::query()
            ->with('media')
            ->where('tenant_id', $tenantId);

        if ($request->has('after')) {
            $model->where('version', '>', $request->after ?? 0);
        }

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;
            $model->whereIn('id', $ids);
        }

        $data = $this->getDataWithFilter(
            model: $model,
            searchBy: ['name'],
            orderBy: $request?->orderBy ?? 'id',
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
