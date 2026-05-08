<?php

namespace App\Actions\Api\V1\Application;

use App\Models\Tenant\Application;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class IndexApplicationAction
{
    use WithGetFilterDataApi;

    /**
     * Handle the action.
     */
    public function handle(Request $request, int $tenantId)
    {
        $model = Application::query()
            ->with('media')
            ->where('tenant_id', $tenantId);

        if ($request->has('ids')) {
            $ids = is_string($request->ids) ? explode(',', $request->ids) : $request->ids;
            $model->whereIn('id', $ids);
        }

        $data = $this->getDataWithFilter(
            model: $model,
            searchBy: ['name', 'package_name'],
            orderBy: $request?->orderBy ?? 'package_name',
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
