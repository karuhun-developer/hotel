<?php

namespace App\Actions\Api\V1\Content;

use App\Models\Tenant\Content\Content;
use App\Traits\WithGetFilterDataApi;
use Illuminate\Http\Request;

class ChangeListContentAction
{
    use WithGetFilterDataApi;

    /**
     * Handle the action.
     */
    public function handle(Request $request, int $tenantId)
    {
        $model = Content::query()
            ->withTrashed()
            ->where('tenant_id', $tenantId)
            ->where('version', '>', $request->after ?? 0)
            ->select('id', 'version', 'deleted_at');

        return $this->getDataWithFilter(
            model: $model,
            searchBy: [],
            orderBy: $request?->orderBy ?? 'id',
            order: $request?->order ?? 'asc',
            paginate: $request?->paginate ?? 10,
            searchBySpecific: $request?->searchBySpecific ?? '',
            s: $request?->search ?? '',
        );
    }
}
